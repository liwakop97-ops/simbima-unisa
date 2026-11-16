<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalReportController extends Controller
{
    public function store(Request $request, Proposal $proposal): RedirectResponse
    {
        abort_if($proposal->user_id !== Auth::id(), 403);
        abort_unless(in_array($proposal->status, ['progress_approved', 'final_submitted'], true), 422);

        $validated = $request->validate([
            'file_laporan_akhir' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'file_sptb_100' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'ringkasan_hasil' => ['required', 'string'],
            'jenis_luaran' => ['required', 'in:jurnal_nasional,jurnal_internasional,prosiding,hki_paten,buku,teknologi_tepat_guna'],
            'judul_luaran' => ['required', 'string'],
            'nama_jurnal_penerbit' => ['nullable', 'string'],
            'status_capaian' => ['required', 'in:draft,submitted,under_review,accepted,published,granted'],
            'url_bukti' => ['nullable', 'url'],
            'file_bukti' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $akhirPath = $request->file('file_laporan_akhir')->store('reports/final', 'public');
        $sptbPath = $request->hasFile('file_sptb_100')
            ? $request->file('file_sptb_100')->store('reports/sptb', 'public')
            : null;
        $buktiPath = $request->hasFile('file_bukti')
            ? $request->file('file_bukti')->store('reports/outputs', 'public')
            : null;

        $proposal->finalReport()->updateOrCreate(
            ['proposal_id' => $proposal->id],
            [
                'file_laporan_akhir' => $akhirPath,
                'file_sptb_100' => $sptbPath,
                'ringkasan_hasil' => $validated['ringkasan_hasil'],
                'status_akhir' => 'submitted',
            ]
        );

        $proposal->outputs()->create([
            'jenis_luaran' => $validated['jenis_luaran'],
            'judul_luaran' => $validated['judul_luaran'],
            'nama_jurnal_penerbit' => $validated['nama_jurnal_penerbit'],
            'status_capaian' => $validated['status_capaian'],
            'url_bukti' => $validated['url_bukti'],
            'file_bukti' => $buktiPath,
        ]);

        $proposal->update(['status' => 'final_submitted']);

        return back()->with('success', 'Laporan akhir & capaian luaran berhasil diunggah.');
    }
}
