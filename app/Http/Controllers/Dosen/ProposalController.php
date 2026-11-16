<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\GrantScheme;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProposalController extends Controller
{
    public function index(): View
    {
        $category = request()->query('kategori');
        $query = Proposal::with([
            'user',
            'scheme',
            'reviews',
            'progressReport',
            'finalReport',
            'outputs',
        ])->withCount('logbooks')
            ->where('user_id', Auth::id());

        if (in_array($category, ['penelitian', 'pengabdian', 'konsorsium', 'prototipe'], true)) {
            $query->whereHas('scheme', fn ($scheme) => $scheme->where('kategori', $category));
        }

        $proposals = $query->latest()->paginate(10)->withQueryString();

        $tab = request()->query('tab', 'usulan');
        $researchTabs = ['usulan', 'bimtek', 'perbaikan-usulan', 'catatan-harian', 'catatan-anggaran', 'laporan-kemajuan', 'laporan-akhir', 'capaian-luaran'];
        $program = request()->query('program');

        if (! in_array($tab, $researchTabs, true)) {
            $tab = 'usulan';
        }

        return view('dosen.proposals.index', compact('proposals', 'category', 'tab', 'program'));
    }

    public function create(): View
    {
        $category = request()->query('kategori');
        $schemes = GrantScheme::where('is_active', true)
            ->when(in_array($category, ['penelitian', 'pengabdian', 'konsorsium', 'prototipe'], true), fn ($query) => $query->where('kategori', $category))
            ->get();
        return view('dosen.proposals.create', compact('schemes', 'category'));
    }

    public function downloadTemplate(GrantScheme $scheme): StreamedResponse
    {
        $templatePath = $scheme->templatePath();
        abort_if($templatePath === null, 404, 'Template untuk skema ini belum tersedia.');

        $extension = pathinfo($templatePath, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $templatePath,
            'Template Proposal ' . $scheme->kode_skema . '.' . $extension
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'grant_scheme_id' => ['required', 'exists:grant_schemes,id'],
            'judul' => ['required', 'string', 'max:500'],
            'abstrak' => ['required', 'string'],
            'kata_kunci' => ['required', 'string', 'max:255'],
            'lama_kegiatan_tahun' => ['required', 'integer', 'min:1', 'max:3'],
            'dana_usulan' => ['required', 'numeric', 'min:1000000'],
            'file_proposal' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'action' => ['required', 'in:draft,submit'],
            'members' => ['nullable', 'array'],
            'members.*.nama_anggota' => ['required_with:members', 'string'],
            'members.*.nidn_nim' => ['nullable', 'string'],
            'members.*.peran' => ['required_with:members', 'in:anggota_dosen,mahasiswa,mitra'],
            'budgets' => ['nullable', 'array'],
            'budgets.*.kelompok_rab' => ['required_with:budgets', 'string'],
            'budgets.*.item_belanja' => ['required_with:budgets', 'string'],
            'budgets.*.volume' => ['required_with:budgets', 'integer', 'min:1'],
            'budgets.*.satuan' => ['required_with:budgets', 'string'],
            'budgets.*.harga_satuan' => ['required_with:budgets', 'numeric', 'min:0'],
        ]);

        $scheme = GrantScheme::findOrFail($validated['grant_scheme_id']);
        if ($validated['dana_usulan'] > $scheme->plafon_maksimal) {
            return back()->withErrors([
                'dana_usulan' => 'Dana usulan melebihi plafon maksimal skema (Rp ' . number_format($scheme->plafon_maksimal, 0, ',', '.') . ').',
            ])->withInput();
        }

        $proposal = DB::transaction(function () use ($request, $validated) {
            $path = $request->file('file_proposal')->store('proposals', 'public');
            $isSubmit = $validated['action'] === 'submit';

            $proposal = Proposal::create([
                'kode_registrasi' => 'BIMA-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'grant_scheme_id' => $validated['grant_scheme_id'],
                'user_id' => Auth::id(),
                'judul' => $validated['judul'],
                'abstrak' => $validated['abstrak'],
                'kata_kunci' => $validated['kata_kunci'],
                'lama_kegiatan_tahun' => $validated['lama_kegiatan_tahun'],
                'dana_usulan' => $validated['dana_usulan'],
                'file_proposal' => $path,
                'status' => $isSubmit ? 'submitted' : 'draft',
                'submitted_at' => $isSubmit ? now() : null,
            ]);

            if (! empty($validated['members'])) {
                foreach ($validated['members'] as $member) {
                    if (! empty($member['nama_anggota'])) {
                        $proposal->members()->create($member);
                    }
                }
            }

            if (! empty($validated['budgets'])) {
                foreach ($validated['budgets'] as $budget) {
                    if (! empty($budget['item_belanja'])) {
                        $proposal->budgets()->create([
                            ...$budget,
                            'subtotal' => $budget['volume'] * $budget['harga_satuan'],
                        ]);
                    }
                }
            }

            return $proposal;
        });

        return redirect()->route('dosen.proposals.show', $proposal)
            ->with('success', 'Proposal berhasil disimpan dengan kode ' . $proposal->kode_registrasi);
    }

    public function show(Proposal $proposal): View
    {
        abort_if($proposal->user_id !== Auth::id(), 403);
        $proposal->load([
            'scheme', 'members', 'budgets', 'reviews.reviewer',
            'logbooks', 'progressReport', 'finalReport', 'outputs'
        ]);

        return view('dosen.proposals.show', compact('proposal'));
    }

    public function submitDraft(Proposal $proposal): RedirectResponse
    {
        abort_if($proposal->user_id !== Auth::id() || $proposal->status !== 'draft', 403);

        $proposal->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Proposal resmi diajukan ke LPPM.');
    }

    /**
     * Unggah Revisi Proposal & Penyesuaian RAB setelah status accepted_revision
     */
    public function uploadRevision(Request $request, Proposal $proposal): RedirectResponse
    {
        abort_if($proposal->user_id !== Auth::id(), 403);
        abort_unless(in_array($proposal->status, ['accepted_revision', 'accepted'], true), 422);

        $request->validate([
            'file_revisi_proposal' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file_revisi_proposal')->store('proposals/revisions', 'public');

        $proposal->update([
            'file_revisi_proposal' => $path,
            'status' => 'revision_submitted',
        ]);

        return back()->with('success', 'Dokumen revisi proposal berhasil diunggah dan menunggu pengesahan LPPM.');
    }
}
