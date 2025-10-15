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
        // Đảm bảo admin@gmail.com có role admin
        $adminUser = \App\Models\User::where('email', 'admin@gmail.com')->first();
        if ($adminUser) {
            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            if ($adminRole && !$adminUser->hasRole('admin')) {
                $adminUser->assignRole($adminRole);
            }
        }

        // 2. Tạo Manager user
        $managerUser = \App\Models\User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'fullname' => 'Manager User',
                'username' => 'manager',
                'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make('@manager123'),
                'level' => 2,
                'status' => 1,
            ]
        );
        $managerRole = \App\Models\Role::where('name', 'manager')->first();
        if ($managerRole && !$managerUser->hasRole('manager')) {
            $managerUser->assignRole($managerRole);
        }

        // 3. Tạo Staff user
        $staffUser = \App\Models\User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'fullname' => 'Staff User',
                'username' => 'staff',
                'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make('@staff123'),
                'level' => 3,
                'status' => 1,
            ]
        );
        $staffRole = \App\Models\Role::where('name', 'staff')->first();
        if ($staffRole && !$staffUser->hasRole('staff')) {
            $staffUser->assignRole($staffRole);
        }

        // 4. Tạo Editor user (chỉ có quyền content)
        $editorUser = \App\Models\User::firstOrCreate(
            ['email' => 'editor@gmail.com'],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'fullname' => 'Content Editor',
                'username' => 'editor',
                'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make('@editor123'),
                'level' => 3,
                'status' => 1,
            ]
        );

        // Gán permissions trực tiếp cho Editor (chỉ content management)
        $editorPermissions = [
            'cate_product.view', 'cate_product.create', 'cate_product.edit',
            'product.view', 'product.create', 'product.edit',
            'cate_news.view', 'cate_news.create', 'cate_news.edit',
            'news.view', 'news.create', 'news.edit',
            'page.view', 'page.create', 'page.edit',
            'menu.view', 'slider.view'
        ];
        
        $editorUser->syncDirectPermissions($editorPermissions);

        $this->command->info('✅ Đã tạo test users với quyền hạn:');
        $this->command->info('🔴 ADMIN: admin@gmail.com (password: @admin123) - Toàn quyền (61 permissions)');
        $this->command->info('🟡 MANAGER: manager@gmail.com (password: @manager123) - Quản lý (40+ permissions)');
        $this->command->info('🟢 STAFF: staff@gmail.com (password: @staff123) - Nhân viên (15+ permissions)');
        $this->command->info('🔵 EDITOR: editor@gmail.com (password: @editor123) - Biên tập nội dung (14 permissions)');
    }
}
