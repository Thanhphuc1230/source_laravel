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
        $adminRole = \App\Models\Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Quản trị viên',
                'description' => 'Quyền quản trị toàn bộ hệ thống'
            ]
        );

        $managerRole = \App\Models\Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Quản lý',
                'description' => 'Quyền quản lý các module chính'
            ]
        );

        $staffRole = \App\Models\Role::firstOrCreate(
            ['name' => 'staff'],
            [
                'display_name' => 'Nhân viên',
                'description' => 'Quyền cơ bản cho nhân viên'
            ]
        );

        $viewerRole = \App\Models\Role::firstOrCreate(
            ['name' => 'viewer'],
            [
                'display_name' => 'Người xem',
                'description' => 'Quyền chỉ xem các thông tin'
            ]
        );

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
            // Cấu hình sản phẩm - đầy đủ
            'product_setting.view', 'product_setting.create', 'product_setting.edit', 'product_setting.delete',
            // Danh mục tin tức - đầy đủ
            'cate_news.view', 'cate_news.create', 'cate_news.edit', 'cate_news.delete',
            // Tin tức - đầy đủ
            'news.view', 'news.create', 'news.edit', 'news.delete',
            // Slider - đầy đủ
            'slider.view', 'slider.create', 'slider.edit', 'slider.delete',
            // Brand - đầy đủ
            'brand.view', 'brand.create', 'brand.edit', 'brand.delete',
            // Feature - đầy đủ
            'feature.view', 'feature.create', 'feature.edit', 'feature.delete',
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
            'analytics.view',
            // Page - đầy đủ
            'page.view', 'page.create', 'page.edit', 'page.delete',
            // Order - đầy đủ
            'order.view', 'order.edit', 'order.delete',
            // Profile - xem và sửa
            'profile.view', 'profile.edit',
            // Role - chỉ xem
            'role.view',
            // Permission - chỉ xem
            'permission.view',
            // Chat - đầy đủ
            'chat.view', 'chat.reply', 'chat.manage'
        ])->pluck('id')->toArray();
        
        $managerRole->syncPermissions($managerPermissions);

        // Staff có permissions hạn chế
        $staffPermissions = \App\Models\Permission::whereIn('name', [
            // Danh mục sản phẩm - chỉ xem
            'cate_product.view',
            // Sản phẩm - xem, tạo, sửa (không xóa)
            'product.view', 'product.create', 'product.edit',
            // Cấu hình sản phẩm - chỉ xem
            'product_setting.view',
            // Danh mục tin tức - chỉ xem
            'cate_news.view',
            // Tin tức - xem, tạo, sửa (không xóa)
            'news.view', 'news.create', 'news.edit',
            // Slider - chỉ xem
            'slider.view',
            // Brand - chỉ xem
            'brand.view',
            // Feature - chỉ xem
            'feature.view',
            // Menu - chỉ xem
            'menu.view',
            // Feedback - chỉ xem
            'feedback.view',
            // Contact - chỉ xem
            'contact.view',
            // Comment - chỉ xem
            'comment.view',
            // Page - xem và sửa
            'page.view', 'page.edit',
            // Order - chỉ xem
            'order.view',
            // Profile - xem và sửa
            'profile.view', 'profile.edit',
            // Chat - chỉ xem và trả lời (không quản lý)
            'chat.view', 'chat.reply'
        ])->pluck('id')->toArray();
        
        $staffRole->syncPermissions($staffPermissions);

        // Viewer chỉ có quyền xem
        $viewerPermissions = \App\Models\Permission::whereIn('name', [
            // Danh mục sản phẩm - chỉ xem
            'cate_product.view',
            // Sản phẩm - chỉ xem
            'product.view',
            // Cấu hình sản phẩm - chỉ xem
            'product_setting.view',
            // Danh mục tin tức - chỉ xem
            'cate_news.view',
            // Tin tức - chỉ xem
            'news.view',
            // Slider - chỉ xem
            'slider.view',
            // Brand - chỉ xem
            'brand.view',
            // Feature - chỉ xem
            'feature.view',
            // Menu - chỉ xem
            'menu.view',
            // Feedback - chỉ xem
            'feedback.view',
            // Contact - chỉ xem
            'contact.view',
            // Comment - chỉ xem
            'comment.view',
            // User - chỉ xem
            'user.view',
            // Analytics - xem thống kê
            'analytics.view',
            // Page - chỉ xem
            'page.view',
            // Order - chỉ xem
            'order.view',
            // Profile - xem và sửa
            'profile.view', 'profile.edit',
            // Role - chỉ xem
            'role.view',
            // Permission - chỉ xem
            'permission.view',
            // Chat - chỉ xem
            'chat.view'
        ])->pluck('id')->toArray();
        
        $viewerRole->syncPermissions($viewerPermissions);
    }
}