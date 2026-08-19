<?php

namespace App\Services;

use App\Models\CateService;
use App\Models\Service;
use App\Models\CateProduct;
use App\Models\Product;

class ServiceService
{
    public function getCategoryServiceData($id_cate_service)
    {
        $data = [];

        $data['category_detail'] = CateService::where('status', 1)
            ->where('id_cate_service', $id_cate_service)
            ->select('id_cate_service', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status')
            ->firstOrFail();

        $data['services'] = Service::where('category_id', $data['category_detail']->id_cate_service)
            ->where('status', 1)
            ->select('id_service', 'name_vn', 'name_en', 'intro_vn', 'intro_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at', 'category_id')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    public function getDetailServiceData($id_service)
    {
        $data = [];

        $data['service_detail'] = Service::with(['cate' => function ($query) {
            $query->select('id_cate_service', 'name_vn', 'name_en', 'slug_vn', 'slug_en');
        }])
            ->where('id_service', $id_service)
            ->select('id_service', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'content_vn', 'content_en', 'created_at', 'category_id', 'keyword_vn', 'keyword_en', 'description_vn', 'description_en')
            ->firstOrFail();

        $data['related_services'] = Service::where('category_id', $data['service_detail']->category_id)
            ->where('status', 1)
            ->where('id_service', '!=', $data['service_detail']->id_service)
            ->select('id_service', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    private function getCommonFrontendData()
    {
        return [
            'category_product' => CateProduct::where('status', 1)
                ->where('parent_id', 0)
                ->select('name_vn', 'name_en', 'status', 'slug_vn', 'slug_en')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),

            'product_hot' => Product::where('status', 1)
                ->where('hot', 1)
                ->select('name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'price', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }
}
