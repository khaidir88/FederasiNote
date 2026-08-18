<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Cek dan buat jika belum ada
        foreach (['Super Admin', 'Admin', 'Petugas', 'Manager', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Assign role ke user tertentu
        $adminUser = \App\Models\User::where('email', 'superadmin@gmail.com')->first();
        if ($adminUser && !$adminUser->hasRole('Super Admin')) {
            $adminUser->assignRole('Super Admin');
        }
    }
}
