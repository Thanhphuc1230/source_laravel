<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function categoryProduct($slug_cate_product)
    {
        $data = $this->productService->getCategoryProductData($slug_cate_product, request());

        return view('frontend.modules.product.category', $data);
    }

    public function detailProduct($slug_product)
    {
        $data = $this->productService->getDetailProductData($slug_product);

        return view('frontend.modules.product.detail', $data);
    }
}
