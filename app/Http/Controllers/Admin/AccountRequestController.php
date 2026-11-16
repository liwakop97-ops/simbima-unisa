<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountRequestController extends Controller
{
    public function index(): View
    {
        $requests = User::where('role', 'dosen')
            ->where('account_status', 'pending')
            ->oldest()
            ->paginate(20);

        return view('admin.accounts.requests', compact('requests'));
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->account_status === 'pending', 404);

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'lppm', 'dosen', 'reviewer'])],
        ]);

        $user->update([
            'role' => $validated['role'],
            'account_status' => 'active',
        ]);

        return back()->with('success', 'Akun ' . $user->name . ' sudah aktif dengan role ' . strtoupper($validated['role']) . '.');
    }

    public function reject(User $user): RedirectResponse
    {
        abort_unless($user->account_status === 'pending', 404);
        $user->update(['account_status' => 'rejected']);

        return back()->with('success', 'Permintaan akun ' . $user->name . ' ditolak.');
    }
}
