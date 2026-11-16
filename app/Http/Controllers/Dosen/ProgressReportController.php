<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressReportController extends Controller
{
    /**
     * Unggah Laporan Kemajuan maupun Revisi Laporan Kemajuan
     */
    public function store(Request $request, Proposal $proposal): RedirectResponse
    {
        abort_if($proposal->user_id !== Auth::id(), 403);
        abort_unless(in_array($proposal->status, ['in_progress', 'progress_revision'], true), 422);

        $validated = $request->validate([
            'file_laporan_kemajuan' => ['required', 'file', 'mimes:pdf', 'max:15360'],
            'file_sptb_70' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'persentase_kemajuan' => ['required', 'integer', 'min:50', 'max:100'],
            'ringkasan_kemajuan' => ['required', 'string'],
        ]);

        $lapPath = $request->file('file_laporan_kemajuan')->store('reports/progress', 'public');
        $sptbPath = $request->hasFile('file_sptb_70')
            ? $request->file('file_sptb_70')->store('reports/sptb', 'public')
            : null;

        $proposal->progressReport()->updateOrCreate(
            ['proposal_id' => $proposal->id],
            [
                'file_laporan_kemajuan' => $lapPath,
                'file_sptb_70' => $sptbPath,
                'persentase_kemajuan' => $validated['persentase_kemajuan'],
                'ringkasan_kemajuan' => $validated['ringkasan_kemajuan'],
                'status_monev' => 'submitted',
            ]
        );

        $proposal->update(['status' => 'progress_submitted']);

        return back()->with('success', 'Laporan kemajuan berhasil diunggah untuk evaluasi Monev.');
    }
}
