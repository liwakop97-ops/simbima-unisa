<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where(function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('nidn_nip', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->where('account_status', $request->string('status')->toString()))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nidn_nip' => ['nullable', 'string', 'max:30', Rule::unique('users', 'nidn_nip')->ignore($user->id)],
            'institusi_prodi' => ['nullable', 'string', 'max:255'],
            'jabatan_fungsional' => ['nullable', 'string', 'max:100'],
            'sinta_id' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', Rule::in(['admin', 'lppm', 'dosen', 'reviewer'])],
            'account_status' => ['required', Rule::in(['active', 'pending', 'rejected'])],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $removingActiveAdmin = $user->role === 'admin'
            && ($validated['role'] !== 'admin' || $validated['account_status'] !== 'active');

        if ($removingActiveAdmin && User::where('role', 'admin')->where('account_status', 'active')->count() <= 1) {
            throw ValidationException::withMessages([
                'role' => 'Minimal satu akun Admin aktif harus tetap tersedia.',
            ]);
        }

        if ($user->is($request->user()) && ($validated['role'] !== 'admin' || $validated['account_status'] !== 'active')) {
            throw ValidationException::withMessages([
                'role' => 'Anda tidak dapat mencabut role Admin atau menonaktifkan akun sendiri.',
            ]);
        }

        $data = Arr::only($validated, [
            'name', 'username', 'email', 'nidn_nip', 'institusi_prodi',
            'jabatan_fungsional', 'sinta_id', 'phone', 'address', 'bio',
            'role', 'account_status',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data akun ' . $user->name . ' berhasil diperbarui.');
    }
}
