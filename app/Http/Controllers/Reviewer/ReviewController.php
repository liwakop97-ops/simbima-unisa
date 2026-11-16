<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ProposalReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = ProposalReview::with(['proposal.user', 'proposal.scheme'])
            ->where('reviewer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('reviewer.reviews.index', compact('reviews'));
    }

    public function show(ProposalReview $review): View
    {
        abort_if($review->reviewer_id !== Auth::id(), 403);
        $review->load(['proposal.user', 'proposal.scheme', 'proposal.members', 'proposal.budgets']);

        return view('reviewer.reviews.show', compact('review'));
    }

    public function submitScore(Request $request, ProposalReview $review): RedirectResponse
    {
        abort_if($review->reviewer_id !== Auth::id(), 403);

        $validated = $request->validate([
            'skor_rumusan_masalah' => ['required', 'integer', 'min:1', 'max:100'],
            'skor_tinjauan_pustaka' => ['required', 'integer', 'min:1', 'max:100'],
            'skor_metode' => ['required', 'integer', 'min:1', 'max:100'],
            'skor_luaran' => ['required', 'integer', 'min:1', 'max:100'],
            'skor_rab' => ['required', 'integer', 'min:1', 'max:100'],
            'rekomendasi' => ['required', 'in:layak,layak_dengan_revisi,tidak_layak'],
            'komentar_reviewer' => ['required', 'string', 'min:10'],
        ]);

        $nilaiTotal = ProposalReview::hitungNilaiTerbobot(
            (int) $validated['skor_rumusan_masalah'],
            (int) $validated['skor_tinjauan_pustaka'],
            (int) $validated['skor_metode'],
            (int) $validated['skor_luaran'],
            (int) $validated['skor_rab']
        );

        $review->update([
            ...$validated,
            'nilai_total' => $nilaiTotal,
            'status_review' => 'completed',
            'reviewed_at' => now(),
        ]);

        return redirect()->route('reviewer.reviews.index')
            ->with('success', 'Penilaian substansi berhasil disimpan dengan skor total: ' . $nilaiTotal);
    }
}
