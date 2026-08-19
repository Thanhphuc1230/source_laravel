<?php

namespace App\Services;

use App\Models\CateProject;
use App\Models\Project;
use App\Models\CateProduct;
use App\Models\Product;

class ProjectService
{
    public function getCategoryProjectData($id_cate_project)
    {
        $data = [];

        $data['category_detail'] = CateProject::where('status', 1)
            ->where('id_cate_project', $id_cate_project)
            ->select('id_cate_project', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status')
            ->firstOrFail();

        $data['projects'] = Project::where('category_id', $data['category_detail']->id_cate_project)
            ->where('status', 1)
            ->select('id_project', 'name_vn', 'name_en', 'intro_vn', 'intro_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at', 'category_id')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    public function getDetailProjectData($id_project)
    {
        $data = [];

        $data['project_detail'] = Project::with(['cate' => function ($query) {
            $query->select('id_cate_project', 'name_vn', 'name_en', 'slug_vn', 'slug_en');
        }])
            ->where('id_project', $id_project)
            ->select('id_project', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'content_vn', 'content_en', 'created_at', 'category_id', 'keyword_vn', 'keyword_en', 'description_vn', 'description_en')
            ->firstOrFail();

        $data['related_projects'] = Project::where('category_id', $data['project_detail']->category_id)
            ->where('status', 1)
            ->where('id_project', '!=', $data['project_detail']->id_project)
            ->select('id_project', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at')
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
