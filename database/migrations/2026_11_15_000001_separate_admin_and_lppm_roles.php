<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    private const LPPM_EMAIL = 'admin@lppm.ac.id';

    private const ADMIN_EMAIL = 'administrator@lppm.ac.id';

    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(20) NOT NULL DEFAULT 'dosen'");

        $legacyAdmin = DB::table('users')->where('email', self::LPPM_EMAIL)->first();

        if ($legacyAdmin) {
            $lppmUsernameAvailable = ! DB::table('users')
                ->where('username', 'lppm')
                ->where('id', '<>', $legacyAdmin->id)
                ->exists();

            DB::table('users')->where('id', $legacyAdmin->id)->update([
                'name' => 'Operator LPPM Pusat',
                'username' => $lppmUsernameAvailable ? 'lppm' : $legacyAdmin->username,
                'jabatan_fungsional' => 'Operator LPPM',
                'role' => 'lppm',
                'account_status' => 'active',
                'updated_at' => now(),
            ]);
        }

        if (! DB::table('users')->where('role', 'admin')->exists()) {
            $adminUsername = DB::table('users')->where('username', 'admin')->exists()
                ? 'superadmin'
                : 'admin';

            DB::table('users')->insert([
                'name' => 'Administrator SIMBIMA',
                'username' => $adminUsername,
                'email' => self::ADMIN_EMAIL,
                'nidn_nip' => null,
                'institusi_prodi' => 'Universitas Aisyiyah Palembang',
                'jabatan_fungsional' => 'Administrator Sistem',
                'sinta_id' => null,
                'role' => 'admin',
                'account_status' => 'active',
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $legacyLppm = DB::table('users')->where('email', self::LPPM_EMAIL)->first();

        if ($legacyLppm) {
            $adminUsernameAvailable = ! DB::table('users')
                ->where('username', 'admin')
                ->where('id', '<>', $legacyLppm->id)
                ->exists();

            DB::table('users')->where('id', $legacyLppm->id)->update([
                'username' => $adminUsernameAvailable ? 'admin' : $legacyLppm->username,
                'jabatan_fungsional' => 'Admin Sistem',
                'role' => 'admin',
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->where('role', 'lppm')->update(['role' => 'admin']);
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'dosen', 'reviewer') NOT NULL DEFAULT 'dosen'");
    }
};
