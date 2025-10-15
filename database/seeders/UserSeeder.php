<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'uuid' => Str::uuid(),
            'fullname' => 'Admin',
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('@admin123'),
            'level' => '1',
            'avatar' => null,
            'created_at' => now(),
        ]);

        // Gán role admin cho user admin
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }
    }
}
