<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function store(Request $request, Proposal $proposal): RedirectResponse
    {
        abort_if($proposal->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'tanggal_kegiatan' => ['required', 'date'],
            'uraian_kegiatan' => ['required', 'string'],
            'persentase_capaian' => ['required', 'integer', 'min:1', 'max:100'],
            'file_dokumentasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $docPath = $request->hasFile('file_dokumentasi')
            ? $request->file('file_dokumentasi')->store('reports/logbooks', 'public')
            : null;

        $proposal->logbooks()->create([
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'uraian_kegiatan' => $validated['uraian_kegiatan'],
            'persentase_capaian' => $validated['persentase_capaian'],
            'file_dokumentasi' => $docPath,
        ]);

        return back()->with('success', 'Catatan harian (Logbook) berhasil ditambahkan.');
    }
}
