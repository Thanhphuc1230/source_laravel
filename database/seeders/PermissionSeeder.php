<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Category Product permissions (Danh mục sản phẩm)
            [
                'name' => 'cate_product.view',
                'display_name' => 'Xem danh mục sản phẩm',
                'group_name' => 'cate_product',
                'description' => 'Quyền xem danh sách danh mục sản phẩm'
            ],
            [
                'name' => 'cate_product.create',
                'display_name' => 'Tạo danh mục sản phẩm',
                'group_name' => 'cate_product',
                'description' => 'Quyền tạo danh mục sản phẩm mới'
            ],
            [
                'name' => 'cate_product.edit',
                'display_name' => 'Sửa danh mục sản phẩm',
                'group_name' => 'cate_product',
                'description' => 'Quyền chỉnh sửa danh mục sản phẩm'
            ],
            [
                'name' => 'cate_product.delete',
                'display_name' => 'Xóa danh mục sản phẩm',
                'group_name' => 'cate_product',
                'description' => 'Quyền xóa danh mục sản phẩm'
            ],

            // Product permissions (Sản phẩm)
            [
                'name' => 'product.view',
                'display_name' => 'Xem sản phẩm',
                'group_name' => 'product',
                'description' => 'Quyền xem danh sách sản phẩm'
            ],
            [
                'name' => 'product.create',
                'display_name' => 'Tạo sản phẩm',
                'group_name' => 'product',
                'description' => 'Quyền tạo sản phẩm mới'
            ],
            [
                'name' => 'product.edit',
                'display_name' => 'Sửa sản phẩm',
                'group_name' => 'product',
                'description' => 'Quyền chỉnh sửa sản phẩm'
            ],
            [
                'name' => 'product.delete',
                'display_name' => 'Xóa sản phẩm',
                'group_name' => 'product',
                'description' => 'Quyền xóa sản phẩm'
            ],

            // Category News permissions (Danh mục tin tức)
            [
                'name' => 'cate_news.view',
                'display_name' => 'Xem danh mục tin tức',
                'group_name' => 'cate_news',
                'description' => 'Quyền xem danh sách danh mục tin tức'
            ],
            [
                'name' => 'cate_news.create',
                'display_name' => 'Tạo danh mục tin tức',
                'group_name' => 'cate_news',
                'description' => 'Quyền tạo danh mục tin tức mới'
            ],
            [
                'name' => 'cate_news.edit',
                'display_name' => 'Sửa danh mục tin tức',
                'group_name' => 'cate_news',
                'description' => 'Quyền chỉnh sửa danh mục tin tức'
            ],
            [
                'name' => 'cate_news.delete',
                'display_name' => 'Xóa danh mục tin tức',
                'group_name' => 'cate_news',
                'description' => 'Quyền xóa danh mục tin tức'
            ],

            // News permissions (Tin tức)
            [
                'name' => 'news.view',
                'display_name' => 'Xem tin tức',
                'group_name' => 'news',
                'description' => 'Quyền xem danh sách tin tức'
            ],
            [
                'name' => 'news.create',
                'display_name' => 'Tạo tin tức',
                'group_name' => 'news',
                'description' => 'Quyền tạo tin tức mới'
            ],
            [
                'name' => 'news.edit',
                'display_name' => 'Sửa tin tức',
                'group_name' => 'news',
                'description' => 'Quyền chỉnh sửa tin tức'
            ],
            [
                'name' => 'news.delete',
                'display_name' => 'Xóa tin tức',
                'group_name' => 'news',
                'description' => 'Quyền xóa tin tức'
            ],

            // Slider permissions (Banner/Slider)
            [
                'name' => 'slider.view',
                'display_name' => 'Xem slider',
                'group_name' => 'slider',
                'description' => 'Quyền xem danh sách slider'
            ],
            [
                'name' => 'slider.create',
                'display_name' => 'Tạo slider',
                'group_name' => 'slider',
                'description' => 'Quyền tạo slider mới'
            ],
            [
                'name' => 'slider.edit',
                'display_name' => 'Sửa slider',
                'group_name' => 'slider',
                'description' => 'Quyền chỉnh sửa slider'
            ],
            [
                'name' => 'slider.delete',
                'display_name' => 'Xóa slider',
                'group_name' => 'slider',
                'description' => 'Quyền xóa slider'
            ],

            // Brand permissions (Đối tác)
            [
                'name' => 'brand.view',
                'display_name' => 'Xem đối tác',
                'group_name' => 'brand',
                'description' => 'Quyền xem danh sách đối tác'
            ],
            [
                'name' => 'brand.create',
                'display_name' => 'Tạo đối tác',
                'group_name' => 'brand',
                'description' => 'Quyền tạo đối tác mới'
            ],
            [
                'name' => 'brand.edit',
                'display_name' => 'Sửa đối tác',
                'group_name' => 'brand',
                'description' => 'Quyền chỉnh sửa đối tác'
            ],
            [
                'name' => 'brand.delete',
                'display_name' => 'Xóa đối tác',
                'group_name' => 'brand',
                'description' => 'Quyền xóa đối tác'
            ],

            // Feature permissions (Tính năng)
            [
                'name' => 'feature.view',
                'display_name' => 'Xem tính năng',
                'group_name' => 'feature',
                'description' => 'Quyền xem danh sách tính năng'
            ],
            [
                'name' => 'feature.create',
                'display_name' => 'Tạo tính năng',
                'group_name' => 'feature',
                'description' => 'Quyền tạo tính năng mới'
            ],
            [
                'name' => 'feature.edit',
                'display_name' => 'Sửa tính năng',
                'group_name' => 'feature',
                'description' => 'Quyền chỉnh sửa tính năng'
            ],
            [
                'name' => 'feature.delete',
                'display_name' => 'Xóa tính năng',
                'group_name' => 'feature',
                'description' => 'Quyền xóa tính năng'
            ],

            // Gallery permissions (Thư viện ảnh)
            [
                'name' => 'gallery.view',
                'display_name' => 'Xem thư viện ảnh',
                'group_name' => 'gallery',
                'description' => 'Quyền xem danh sách thư viện ảnh'
            ],
            [
                'name' => 'gallery.create',
                'display_name' => 'Tạo thư viện ảnh',
                'group_name' => 'gallery',
                'description' => 'Quyền tạo thư viện ảnh mới'
            ],
            [
                'name' => 'gallery.edit',
                'display_name' => 'Sửa thư viện ảnh',
                'group_name' => 'gallery',
                'description' => 'Quyền chỉnh sửa thư viện ảnh'
            ],
            [
                'name' => 'gallery.delete',
                'display_name' => 'Xóa thư viện ảnh',
                'group_name' => 'gallery',
                'description' => 'Quyền xóa thư viện ảnh'
            ],

            // Menu permissions (Menu)
            [
                'name' => 'menu.view',
                'display_name' => 'Xem menu',
                'group_name' => 'menu',
                'description' => 'Quyền xem cấu trúc menu'
            ],
            [
                'name' => 'menu.create',
                'display_name' => 'Tạo menu',
                'group_name' => 'menu',
                'description' => 'Quyền tạo menu mới'
            ],
            [
                'name' => 'menu.edit',
                'display_name' => 'Sửa menu',
                'group_name' => 'menu',
                'description' => 'Quyền chỉnh sửa menu'
            ],
            [
                'name' => 'menu.delete',
                'display_name' => 'Xóa menu',
                'group_name' => 'menu',
                'description' => 'Quyền xóa menu'
            ],

            // Feedback permissions (Phản hồi)
            [
                'name' => 'feedback.view',
                'display_name' => 'Xem phản hồi',
                'group_name' => 'feedback',
                'description' => 'Quyền xem danh sách phản hồi từ khách hàng'
            ],
            [
                'name' => 'feedback.edit',
                'display_name' => 'Sửa phản hồi',
                'group_name' => 'feedback',
                'description' => 'Quyền chỉnh sửa trạng thái phản hồi'
            ],
            [
                'name' => 'feedback.delete',
                'display_name' => 'Xóa phản hồi',
                'group_name' => 'feedback',
                'description' => 'Quyền xóa phản hồi'
            ],

            // Contact permissions (Liên hệ)
            [
                'name' => 'contact.view',
                'display_name' => 'Xem liên hệ',
                'group_name' => 'contact',
                'description' => 'Quyền xem danh sách liên hệ'
            ],
            [
                'name' => 'contact.edit',
                'display_name' => 'Sửa liên hệ',
                'group_name' => 'contact',
                'description' => 'Quyền chỉnh sửa trạng thái liên hệ'
            ],
            [
                'name' => 'contact.delete',
                'display_name' => 'Xóa liên hệ',
                'group_name' => 'contact',
                'description' => 'Quyền xóa liên hệ'
            ],

            // Comment permissions (Bình luận)
            [
                'name' => 'comment.view',
                'display_name' => 'Xem bình luận',
                'group_name' => 'comment',
                'description' => 'Quyền xem danh sách bình luận'
            ],
            [
                'name' => 'comment.moderate',
                'display_name' => 'Duyệt bình luận',
                'group_name' => 'comment',
                'description' => 'Quyền duyệt bình luận'
            ],
            [
                'name' => 'comment.delete',
                'display_name' => 'Xóa bình luận',
                'group_name' => 'comment',
                'description' => 'Quyền xóa bình luận'
            ],

            // User permissions (Người dùng)
            [
                'name' => 'user.view',
                'display_name' => 'Xem người dùng',
                'group_name' => 'user',
                'description' => 'Quyền xem danh sách người dùng'
            ],
            [
                'name' => 'user.create',
                'display_name' => 'Tạo người dùng',
                'group_name' => 'user',
                'description' => 'Quyền tạo người dùng mới'
            ],
            [
                'name' => 'user.edit',
                'display_name' => 'Sửa người dùng',
                'group_name' => 'user',
                'description' => 'Quyền chỉnh sửa thông tin người dùng'
            ],
            [
                'name' => 'user.delete',
                'display_name' => 'Xóa người dùng',
                'group_name' => 'user',
                'description' => 'Quyền xóa người dùng'
            ],

            // System permissions (Hệ thống)
            [
                'name' => 'system.view',
                'display_name' => 'Xem cấu hình hệ thống',
                'group_name' => 'system',
                'description' => 'Quyền xem cấu hình hệ thống'
            ],
            [
                'name' => 'system.edit',
                'display_name' => 'Sửa cấu hình hệ thống',
                'group_name' => 'system',
                'description' => 'Quyền chỉnh sửa cấu hình hệ thống'
            ],

            // Analytics permissions (Thống kê)
            [
                'name' => 'analytics.view',
                'display_name' => 'Xem thống kê',
                'group_name' => 'analytics',
                'description' => 'Quyền xem báo cáo thống kê'
            ],

            // Page permissions (Trang nội dung)
            [
                'name' => 'page.view',
                'display_name' => 'Xem trang nội dung',
                'group_name' => 'page',
                'description' => 'Quyền xem danh sách trang nội dung'
            ],
            [
                'name' => 'page.create',
                'display_name' => 'Tạo trang nội dung',
                'group_name' => 'page',
                'description' => 'Quyền tạo trang nội dung mới'
            ],
            [
                'name' => 'page.edit',
                'display_name' => 'Sửa trang nội dung',
                'group_name' => 'page',
                'description' => 'Quyền chỉnh sửa trang nội dung'
            ],
            [
                'name' => 'page.delete',
                'display_name' => 'Xóa trang nội dung',
                'group_name' => 'page',
                'description' => 'Quyền xóa trang nội dung'
            ],

            // Order permissions (Đơn hàng)
            [
                'name' => 'order.view',
                'display_name' => 'Xem đơn hàng',
                'group_name' => 'order',
                'description' => 'Quyền xem danh sách đơn hàng'
            ],
            [
                'name' => 'order.edit',
                'display_name' => 'Sửa đơn hàng',
                'group_name' => 'order',
                'description' => 'Quyền chỉnh sửa trạng thái đơn hàng'
            ],
            [
                'name' => 'order.delete',
                'display_name' => 'Xóa đơn hàng',
                'group_name' => 'order',
                'description' => 'Quyền xóa đơn hàng'
            ],

            // Product Setting permissions (Cấu hình sản phẩm)
            [
                'name' => 'product_setting.view',
                'display_name' => 'Xem cấu hình sản phẩm',
                'group_name' => 'product_setting',
                'description' => 'Quyền xem cấu hình sản phẩm'
            ],
            [
                'name' => 'product_setting.create',
                'display_name' => 'Tạo cấu hình sản phẩm',
                'group_name' => 'product_setting',
                'description' => 'Quyền tạo cấu hình sản phẩm mới'
            ],
            [
                'name' => 'product_setting.edit',
                'display_name' => 'Sửa cấu hình sản phẩm',
                'group_name' => 'product_setting',
                'description' => 'Quyền chỉnh sửa cấu hình sản phẩm'
            ],
            [
                'name' => 'product_setting.delete',
                'display_name' => 'Xóa cấu hình sản phẩm',
                'group_name' => 'product_setting',
                'description' => 'Quyền xóa cấu hình sản phẩm'
            ],

            // Chat permissions (Chat)
            [
                'name' => 'chat.view',
                'display_name' => 'Xem chat',
                'group_name' => 'chat',
                'description' => 'Quyền xem danh sách chat và tin nhắn'
            ],
            [
                'name' => 'chat.create',
                'display_name' => 'Tạo chat',
                'group_name' => 'chat',
                'description' => 'Quyền tạo phiên chat mới'
            ],
            [
                'name' => 'chat.edit',
                'display_name' => 'Sửa chat',
                'group_name' => 'chat',
                'description' => 'Quyền chỉnh sửa thông tin chat'
            ],
            [
                'name' => 'chat.delete',
                'display_name' => 'Xóa chat',
                'group_name' => 'chat',
                'description' => 'Quyền xóa phiên chat'
            ],

            // Role permissions (Vai trò)
            [
                'name' => 'role.view',
                'display_name' => 'Xem vai trò',
                'group_name' => 'role',
                'description' => 'Quyền xem danh sách vai trò'
            ],
            [
                'name' => 'role.create',
                'display_name' => 'Tạo vai trò',
                'group_name' => 'role',
                'description' => 'Quyền tạo vai trò mới'
            ],
            [
                'name' => 'role.edit',
                'display_name' => 'Sửa vai trò',
                'group_name' => 'role',
                'description' => 'Quyền chỉnh sửa vai trò'
            ],
            [
                'name' => 'role.delete',
                'display_name' => 'Xóa vai trò',
                'group_name' => 'role',
                'description' => 'Quyền xóa vai trò'
            ],

            // Permission permissions (Quyền hạn)
            [
                'name' => 'permission.view',
                'display_name' => 'Xem quyền hạn',
                'group_name' => 'permission',
                'description' => 'Quyền xem danh sách quyền hạn'
            ],
            [
                'name' => 'permission.create',
                'display_name' => 'Tạo quyền hạn',
                'group_name' => 'permission',
                'description' => 'Quyền tạo quyền hạn mới'
            ],
            [
                'name' => 'permission.edit',
                'display_name' => 'Sửa quyền hạn',
                'group_name' => 'permission',
                'description' => 'Quyền chỉnh sửa quyền hạn'
            ],
            [
                'name' => 'permission.delete',
                'display_name' => 'Xóa quyền hạn',
                'group_name' => 'permission',
                'description' => 'Quyền xóa quyền hạn'
            ],

            // Mail Config permissions (Cấu hình mail)
            [
                'name' => 'mail-config.view',
                'display_name' => 'Xem cấu hình mail',
                'group_name' => 'mail_config',
                'description' => 'Quyền xem danh sách cấu hình mail'
            ],
            [
                'name' => 'mail-config.create',
                'display_name' => 'Tạo cấu hình mail',
                'group_name' => 'mail_config',
                'description' => 'Quyền tạo cấu hình mail mới'
            ],
            [
                'name' => 'mail-config.edit',
                'display_name' => 'Sửa cấu hình mail',
                'group_name' => 'mail_config',
                'description' => 'Quyền chỉnh sửa cấu hình mail'
            ],
            [
                'name' => 'mail-config.delete',
                'display_name' => 'Xóa cấu hình mail',
                'group_name' => 'mail_config',
                'description' => 'Quyền xóa cấu hình mail'
            ],

            // Mail Template permissions (Template mail)
            [
                'name' => 'mail-template.view',
                'display_name' => 'Xem template mail',
                'group_name' => 'mail_template',
                'description' => 'Quyền xem danh sách template mail'
            ],
            [
                'name' => 'mail-template.create',
                'display_name' => 'Tạo template mail',
                'group_name' => 'mail_template',
                'description' => 'Quyền tạo template mail mới'
            ],
            [
                'name' => 'mail-template.edit',
                'display_name' => 'Sửa template mail',
                'group_name' => 'mail_template',
                'description' => 'Quyền chỉnh sửa template mail'
            ],
            [
                'name' => 'mail-template.delete',
                'display_name' => 'Xóa template mail',
                'group_name' => 'mail_template',
                'description' => 'Quyền xóa template mail'
            ],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::firstOrCreate(
                ['name' => $permission['name']], 
                $permission
            );
        }
    }
}
