<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrantScheme;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_proposals' => Proposal::count(),
            'submitted' => Proposal::where('status', 'submitted')->count(),
            'under_review' => Proposal::where('status', 'under_review')->count(),
            'accepted' => Proposal::whereIn('status', ['accepted', 'accepted_revision', 'revision_submitted', 'in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed'])->count(),
            'rejected' => Proposal::whereIn('status', ['admin_rejected', 'rejected'])->count(),
            'completed' => Proposal::where('status', 'completed')->count(),
            'total_dana_disetujui' => Proposal::sum('dana_disetujui'),
            'pending_accounts' => auth()->user()->isAdmin()
                ? User::where('account_status', 'pending')->count()
                : 0,
            'total_users' => auth()->user()->isAdmin() ? User::count() : 0,
        ];

        $recentProposals = Proposal::with(['user', 'scheme'])->latest()->take(10)->get();
        $schemes = GrantScheme::withCount('proposals')->get();

        return view('admin.dashboard', compact('stats', 'recentProposals', 'schemes'));
    }
}
