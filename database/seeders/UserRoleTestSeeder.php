<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo test users với roles khác nhau
        
        // 1. Tạo Super Admin user
        $adminUser = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'fullname' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'level' => 2,
            'status' => 1,
        ]);
        $adminUser->assignRole('admin');

        // 2. Tạo Manager user
        $managerUser = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'fullname' => 'Manager User',
            'username' => 'manager',
            'email' => 'manager@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'level' => 1,
            'status' => 1,
        ]);
        $managerUser->assignRole('manager');

        // 3. Tạo Staff user
        $staffUser = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'fullname' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'level' => 0,
            'status' => 1,
        ]);
        $staffUser->assignRole('staff');

        // 4. Tạo user với multiple roles
        $multiUser = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'fullname' => 'Multi Role User',
            'username' => 'multiuser',
            'email' => 'multi@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'level' => 1,
            'status' => 1,
        ]);
        $multiUser->assignRole(['manager', 'staff']);

        $this->command->info('✅ Đã tạo test users với roles:');
        $this->command->info('- admin@test.com (password: 123456) - Role: admin');
        $this->command->info('- manager@test.com (password: 123456) - Role: manager');
        $this->command->info('- staff@test.com (password: 123456) - Role: staff');
        $this->command->info('- multi@test.com (password: 123456) - Roles: manager, staff');
    }
}
