<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ProgressReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonevController extends Controller
{
    public function evaluate(Request $request, ProgressReport $progressReport): RedirectResponse
    {
        $validated = $request->validate([
            'status_monev' => ['required', 'in:approved,needs_revision'],
            'skor_monev' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_monev' => ['required', 'string'],
        ]);

        $progressReport->update([
            'status_monev' => $validated['status_monev'],
            'skor_monev' => $validated['skor_monev'],
            'catatan_monev' => $validated['catatan_monev'],
            'reviewer_id' => Auth::id(),
        ]);

        $nextProposalStatus = $validated['status_monev'] === 'approved'
            ? 'progress_approved'
            : 'progress_revision';

        $progressReport->proposal->update(['status' => $nextProposalStatus]);

        return back()->with('success', 'Hasil Monitoring & Evaluasi (Monev) Laporan Kemajuan berhasil disimpan.');
    }
}
