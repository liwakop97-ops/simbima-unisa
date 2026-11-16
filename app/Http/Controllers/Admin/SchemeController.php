<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrantScheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SchemeController extends Controller
{
    public function index(): View
    {
        $schemes = GrantScheme::withCount('proposals')->latest()->get();
        return view('admin.schemes.index', compact('schemes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_skema' => ['required', 'string', 'max:20', 'unique:grant_schemes,kode_skema'],
            'nama_skema' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:penelitian,pengabdian,konsorsium,prototipe'],
            'tahun_anggaran' => ['required', 'integer', 'min:2024', 'max:2030'],
            'plafon_maksimal' => ['required', 'numeric', 'min:1000000'],
            'tanggal_buka' => ['required', 'date'],
            'tanggal_tutup' => ['required', 'date', 'after_or_equal:tanggal_buka'],
            'template_file' => ['nullable', 'file', 'mimes:doc,docx', 'max:10240'],
        ]);

        $template = $validated['template_file'] ?? null;
        unset($validated['template_file']);

        $scheme = GrantScheme::create($validated);

        if ($template) {
            $this->storeTemplate($scheme, $template);
        }

        return back()->with('success', 'Skema hibah baru berhasil ditambahkan.');
    }

    public function updateTemplate(Request $request, GrantScheme $scheme): RedirectResponse
    {
        $validated = $request->validate([
            'template_file' => ['required', 'file', 'mimes:doc,docx', 'max:10240'],
        ]);

        $this->storeTemplate($scheme, $validated['template_file']);

        return back()->with('success', 'Template skema ' . $scheme->kode_skema . ' berhasil diperbarui.');
    }

    public function destroy(GrantScheme $scheme): RedirectResponse
    {
        if ($scheme->proposals()->exists()) {
            return back()->withErrors([
                'scheme' => 'Skema ' . $scheme->kode_skema . ' tidak dapat dihapus karena sudah memiliki usulan.',
            ]);
        }

        $schemeCode = $scheme->kode_skema;
        $this->deleteTemplate($scheme);
        $scheme->delete();

        return back()->with('success', 'Skema ' . $schemeCode . ' berhasil dihapus.');
    }

    private function storeTemplate(GrantScheme $scheme, UploadedFile $template): void
    {
        $this->deleteTemplate($scheme);

        $extension = strtolower($template->getClientOriginalExtension());
        $template->storeAs(
            'scheme-templates',
            $scheme->templateBaseName() . '.' . $extension,
            'local'
        );
    }

    private function deleteTemplate(GrantScheme $scheme): void
    {
        Storage::disk('local')->delete([
            'scheme-templates/' . $scheme->templateBaseName() . '.docx',
            'scheme-templates/' . $scheme->templateBaseName() . '.doc',
        ]);
    }
}
