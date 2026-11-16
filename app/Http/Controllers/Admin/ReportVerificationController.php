<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportVerificationController extends Controller
{
    public function verifyFinalReport(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'status_akhir' => ['required', 'in:verified,needs_revision'],
            'catatan_akhir' => ['nullable', 'string'],
        ]);

        if ($proposal->finalReport) {
            $proposal->finalReport->update($validated);
        }

        $proposal->update([
            'status' => $validated['status_akhir'] === 'verified' ? 'completed' : 'progress_approved',
            'catatan_admin' => $validated['catatan_akhir'],
        ]);

        return back()->with('success', 'Verifikasi laporan akhir dan luaran berhasil diperbarui.');
    }
}
