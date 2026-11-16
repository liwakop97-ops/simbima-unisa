<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\GrantScheme;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $proposals = Proposal::with(['scheme', 'reviews'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $activeSchemes = GrantScheme::where('is_active', true)
            ->whereDate('tanggal_tutup', '>=', now())
            ->get();

        return view('dosen.dashboard', compact('proposals', 'activeSchemes'));
    }
}
