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
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::create($permission);
        }
    }
}
