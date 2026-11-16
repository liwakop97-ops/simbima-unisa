<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nidn_nip' => ['required', 'string', 'max:30', 'unique:users,nidn_nip'],
            'institusi_prodi' => ['required', 'string', 'max:255'],
            'jabatan_fungsional' => ['nullable', 'string', 'max:100'],
            'sinta_id' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agreement' => ['accepted'],
        ], [
            'agreement.accepted' => 'Persetujuan penggunaan akun wajib dicentang.',
            'nidn_nip.unique' => 'NIDN/NIP tersebut sudah pernah didaftarkan.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nidn_nip' => $validated['nidn_nip'],
            'institusi_prodi' => $validated['institusi_prodi'],
            'jabatan_fungsional' => $validated['jabatan_fungsional'] ?? null,
            'sinta_id' => $validated['sinta_id'] ?? null,
            'role' => 'dosen',
            'account_status' => 'pending',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('success', 'Permintaan akun berhasil dikirim. Akun dapat digunakan setelah diverifikasi oleh Administrator SIMBIMA.');
    }
}
