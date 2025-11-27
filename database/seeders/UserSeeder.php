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
        // Tạo Admin user
        $adminUser = User::create([
            'uuid' => Str::uuid(),
            'fullname' => 'Quản trị viên',
            'username' => 'admin',
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

        // Tạo Manager user
        $managerUser = User::create([
            'uuid' => Str::uuid(),
            'fullname' => 'Nguyễn Văn Quản lý',
            'username' => 'manager',
            'email' => 'manager@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('@admin123'),
            'level' => '2',
            'avatar' => null,
            'created_at' => now(),
        ]);

        // Gán role manager
        $managerRole = \App\Models\Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerUser->assignRole($managerRole);
        }

        // Tạo Staff user
        $staffUser = User::create([
            'uuid' => Str::uuid(),
            'fullname' => 'Trần Thị Nhân viên',
            'username' => 'staff',
            'email' => 'staff@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('@admin123'),
            'level' => '3',
            'avatar' => null,
            'created_at' => now(),
        ]);

        // Gán role staff
        $staffRole = \App\Models\Role::where('name', 'staff')->first();
        if ($staffRole) {
            $staffUser->assignRole($staffRole);
        }

        // Tạo Viewer user
        $viewerUser = User::create([
            'uuid' => Str::uuid(),
            'fullname' => 'Lê Văn Người xem',
            'username' => 'viewer',
            'email' => 'viewer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('@admin123'),
            'level' => '4',
            'avatar' => null,
            'created_at' => now(),
        ]);

        // Gán role viewer
        $viewerRole = \App\Models\Role::where('name', 'viewer')->first();
        if ($viewerRole) {
            $viewerUser->assignRole($viewerRole);
        }
    }
}
