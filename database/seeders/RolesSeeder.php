<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User; // Impor User

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat 3 Roles
        $roleAdmin = Role::create(['name' => 'Admin']);
        $rolePenelaah = Role::create(['name' => 'Penelaah']);
        $rolePengguna = Role::create(['name' => 'Pengguna']);

        // Buat Akun Admin (Kepala Seksi)
        $adminUser = User::factory()->create([
            'name' => 'Admin Kepala Seksi',
            'email' => 'admin@bpkh.com', // Ganti dengan email Anda
            'password' => bcrypt('password123')
        ]);
        $adminUser->assignRole($roleAdmin);

        // Buat Akun Penelaah (Contoh)
        $penelaahUser = User::factory()->create([
            'name' => 'Penelaah Data',
            'email' => 'penelaah@bpkh.com', // Ganti dengan email Anda
            'password' => bcrypt('password123')
        ]);
        $penelaahUser->assignRole($rolePenelaah);
    }
}
