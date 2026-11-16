<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\ProgressReport;
use App\Models\ProposalReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $reviewerId = Auth::id();
        $reviews = ProposalReview::with(['proposal.user', 'proposal.scheme'])
            ->where('reviewer_id', $reviewerId)
            ->latest()
            ->get();

        $monevReports = ProgressReport::with(['proposal.user', 'proposal.scheme'])
            ->where('status_monev', 'submitted')
            ->get();

        return view('reviewer.dashboard', compact('reviews', 'monevReports'));
    }
}
