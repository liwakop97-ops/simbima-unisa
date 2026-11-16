<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProposalManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Proposal::with(['user', 'scheme', 'reviews.reviewer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $proposals = $query->paginate(15);
        return view('admin.proposals.index', compact('proposals'));
    }

    public function show(Proposal $proposal): View
    {
        $proposal->load([
            'user', 'scheme', 'members', 'budgets',
            'reviews.reviewer', 'logbooks', 'progressReport', 'finalReport', 'outputs'
        ]);
        $reviewers = User::where('role', 'reviewer')->get();

        return view('admin.proposals.show', compact('proposal', 'reviewers'));
    }

    /**
     * Tahap 1: Verifikasi Administrasi & Plotting Reviewer
     */
    public function verifyAndAssignReviewer(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan_admin' => ['required', 'in:lolos,tolak'],
            'catatan_admin' => ['nullable', 'string'],
            'reviewer_ids' => ['required_if:keputusan_admin,lolos', 'array', 'min:1'],
            'reviewer_ids.*' => ['exists:users,id'],
        ]);

        if ($validated['keputusan_admin'] === 'tolak') {
            $proposal->update([
                'status' => 'admin_rejected',
                'catatan_admin' => $validated['catatan_admin'],
                'decided_at' => now(),
            ]);
            return back()->with('success', 'Proposal ditolak pada tahap seleksi administrasi.');
        }

        foreach ($validated['reviewer_ids'] as $reviewerId) {
            $proposal->reviews()->firstOrCreate(['reviewer_id' => $reviewerId]);
        }

        $proposal->update([
            'status' => 'under_review',
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return back()->with('success', 'Proposal lolos administrasi dan berhasil di-plotting ke Reviewer.');
    }

    /**
     * Tahap 2: Penetapan Hasil Review (Diterima / Diterima dengan Revisi / Ditolak)
     */
    public function decideFunding(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan_pendanaan' => ['required', 'in:accepted,accepted_revision,rejected'],
            'dana_disetujui' => ['required_unless:keputusan_pendanaan,rejected', 'nullable', 'numeric', 'min:0'],
            'nomor_kontrak' => ['nullable', 'string', 'max:100'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $isRejected = $validated['keputusan_pendanaan'] === 'rejected';

        $proposal->update([
            'status' => $validated['keputusan_pendanaan'],
            'dana_disetujui' => $isRejected ? 0 : $validated['dana_disetujui'],
            'nomor_kontrak' => $isRejected ? null : ($validated['nomor_kontrak'] ?: 'KONTRAK/LPPM/' . date('Y') . '/' . $proposal->id),
            'catatan_admin' => $validated['catatan_admin'],
            'decided_at' => now(),
        ]);

        return back()->with('success', 'Penetapan pendanaan hibah berhasil disimpan.');
    }

    /**
     * Tahap 3: Pengesahan Revisi Proposal & Mulai Pelaksanaan (in_progress)
     */
    public function approveRevision(Proposal $proposal): RedirectResponse
    {
        abort_unless(in_array($proposal->status, ['revision_submitted', 'accepted'], true), 422);

        $proposal->update(['status' => 'in_progress']);

        return back()->with('success', 'Revisi disahkan! Status proposal kini aktif dalam masa pelaksanaan penelitian/pengabdian.');
    }
}
