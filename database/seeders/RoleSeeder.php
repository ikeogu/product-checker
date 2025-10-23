<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'company']);
        Role::firstOrCreate(['name' => 'user']);

        $user = User::firstOrCreate([
            'email' => 'admin@productverifier.com'
        ], [
            'name' => 'System Admin',
            'password' => 'password123',
            'provider' => 'local',
        ]);

        $user->assignRole('super_admin');
    }
}
