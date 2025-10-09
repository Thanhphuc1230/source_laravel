<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các roles
        $adminRole = \App\Models\Role::create([
            'name' => 'admin',
            'display_name' => 'Quản trị viên',
            'description' => 'Quyền quản trị toàn bộ hệ thống'
        ]);

        $managerRole = \App\Models\Role::create([
            'name' => 'manager',
            'display_name' => 'Quản lý',
            'description' => 'Quyền quản lý các module chính'
        ]);

        $staffRole = \App\Models\Role::create([
            'name' => 'staff',
            'display_name' => 'Nhân viên',
            'description' => 'Quyền cơ bản cho nhân viên'
        ]);

        // Lấy tất cả permissions
        $allPermissions = \App\Models\Permission::all()->pluck('id')->toArray();

        // Admin có TẤT CẢ permissions
        $adminRole->syncPermissions($allPermissions);

        // Manager có permissions
        $managerPermissions = \App\Models\Permission::whereIn('name', [
            // Danh mục sản phẩm - đầy đủ
            'cate_product.view', 'cate_product.create', 'cate_product.edit', 'cate_product.delete',
            // Sản phẩm - đầy đủ
            'product.view', 'product.create', 'product.edit', 'product.delete',
            // Danh mục tin tức - đầy đủ
            'cate_news.view', 'cate_news.create', 'cate_news.edit', 'cate_news.delete',
            // Tin tức - đầy đủ
            'news.view', 'news.create', 'news.edit', 'news.delete',
            // Slider - đầy đủ
            'slider.view', 'slider.create', 'slider.edit', 'slider.delete',
            // Menu - đầy đủ
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
            // Feedback - xem và sửa
            'feedback.view', 'feedback.edit',
            // Contact - xem và sửa
            'contact.view', 'contact.edit',
            // Comment - đầy đủ
            'comment.view', 'comment.moderate', 'comment.delete',
            // User - chỉ xem
            'user.view',
            // Analytics - xem thống kê
            'analytics.view'
        ])->pluck('id')->toArray();
        
        $managerRole->syncPermissions($managerPermissions);

        // Staff có permissions hạn chế
        $staffPermissions = \App\Models\Permission::whereIn('name', [
            // Danh mục sản phẩm - chỉ xem
            'cate_product.view',
            // Sản phẩm - xem, tạo, sửa (không xóa)
            'product.view', 'product.create', 'product.edit',
            // Danh mục tin tức - chỉ xem
            'cate_news.view',
            // Tin tức - xem, tạo, sửa (không xóa)
            'news.view', 'news.create', 'news.edit',
            // Slider - chỉ xem
            'slider.view',
            // Menu - chỉ xem
            'menu.view',
            // Feedback - chỉ xem
            'feedback.view',
            // Contact - chỉ xem
            'contact.view',
            // Comment - chỉ xem
            'comment.view'
        ])->pluck('id')->toArray();
        
        $staffRole->syncPermissions($staffPermissions);
    }
}
