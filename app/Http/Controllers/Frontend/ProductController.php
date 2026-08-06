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

    public function categoryProduct($id_cate_product)
    {
        $data = $this->productService->getCategoryProductData($id_cate_product, request());

        return view('frontend.modules.product.category', $data);
    }

    public function detailProduct($id_product)
    {
        $data = $this->productService->getDetailProductData($id_product);

        return view('frontend.modules.product.detail', $data);
    }

    public function brandProduct($id_brand)
    {
        $data = $this->productService->getBrandProductData($id_brand, request());

        return view('frontend.modules.product.category', $data);
    }
}
