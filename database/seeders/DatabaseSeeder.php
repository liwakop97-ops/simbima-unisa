<?php

namespace Database\Seeders;

use App\Models\GrantScheme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Default (Administrator, Operator LPPM, Dosen Pengusul, Reviewer)
        User::updateOrCreate(['email' => 'admin@lppm.ac.id'], [
            'name' => 'Operator LPPM Pusat',
            'username' => 'lppm',
            'nidn_nip' => '198501012010011001',
            'institusi_prodi' => 'LPPM Universitas Aisyiyah Palembang',
            'jabatan_fungsional' => 'Operator LPPM',
            'role' => 'lppm',
            'account_status' => 'active',
            'password' => Hash::make('123456'),
        ]);

        User::updateOrCreate(['email' => 'administrator@lppm.ac.id'], [
            'name' => 'Administrator SIMBIMA',
            'username' => 'admin',
            'nidn_nip' => null,
            'institusi_prodi' => 'Universitas Aisyiyah Palembang',
            'jabatan_fungsional' => 'Administrator Sistem',
            'role' => 'admin',
            'account_status' => 'active',
            'password' => Hash::make('123456'),
        ]);

        User::updateOrCreate(['email' => 'dosen@lppm.ac.id'], [
            'name' => 'Dr. Budi Santoso, M.Kom.',
            'username' => 'dosen',
            'nidn_nip' => '0215088801',
            'institusi_prodi' => 'Teknologi Informasi',
            'jabatan_fungsional' => 'Lektor Kepala',
            'sinta_id' => '6012345',
            'role' => 'dosen',
            'password' => Hash::make('123456'),
        ]);

        User::updateOrCreate(['email' => 'reviewer@lppm.ac.id'], [
            'name' => 'Prof. Dr. Siti Aminah, M.T.',
            'username' => 'review',
            'nidn_nip' => '0012047502',
            'institusi_prodi' => 'Keperawatan',
            'jabatan_fungsional' => 'Guru Besar',
            'sinta_id' => '5987654',
            'role' => 'reviewer',
            'password' => Hash::make('123456'),
        ]);

        // 2. Skema Hibah Standar BIMA Kemdiktisaintek
        GrantScheme::updateOrCreate(['kode_skema' => 'PDP-2026'], [
            'nama_skema' => 'Penelitian Dosen Pemula (PDP)',
            'kategori' => 'penelitian',
            'tahun_anggaran' => 2026,
            'plafon_maksimal' => 50000000,
            'tanggal_buka' => '2026-01-10',
            'tanggal_tutup' => '2026-12-31',
            'is_active' => true,
        ]);

        GrantScheme::updateOrCreate(['kode_skema' => 'PFR-2026'], [
            'nama_skema' => 'Penelitian Fundamental Reguler (PFR)',
            'kategori' => 'penelitian',
            'tahun_anggaran' => 2026,
            'plafon_maksimal' => 150000000,
            'tanggal_buka' => '2026-01-10',
            'tanggal_tutup' => '2026-12-31',
            'is_active' => true,
        ]);

        GrantScheme::updateOrCreate(['kode_skema' => 'PKM-2026'], [
            'nama_skema' => 'Pemberdayaan Kemitraan Masyarakat (PKM)',
            'kategori' => 'pengabdian',
            'tahun_anggaran' => 2026,
            'plafon_maksimal' => 60000000,
            'tanggal_buka' => '2026-01-10',
            'tanggal_tutup' => '2026-12-31',
            'is_active' => true,
        ]);
    }
}
