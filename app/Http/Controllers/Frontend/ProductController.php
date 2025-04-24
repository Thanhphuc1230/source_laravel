<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CateProduct;
use App\Models\Product;
class ProductController extends Controller
{
    public function categoryProduct($slug_cate_product)
    {   
        $data['category_detail'] = CateProduct::where('status',1)->where('slug',$slug_cate_product)->firstOrFail();
        // category product
        $data['category_product'] = CateProduct::where('status',1)->where('home',1)->orderBy('stt','asc')->where('parent_id',0)->get();

        $id_category_child = CateProduct::where('parent_id',$data['category_detail']->id_cate_product)->pluck('id_cate_product');
        $id_category_child = $id_category_child->merge([$data['category_detail']->id_cate_product]);
        // product
        $data['products'] = Product::with('category')->whereIn('category_id',$id_category_child)
        ->orderBy('stt','asc')
        ->where('status',1)->paginate(16)->withQueryString();;
        
        return view('frontend.modules.product.category',$data);
    }

    public function detailProduct($slug_product,$id_product)
    {
        $data['product_detail'] = Product::where('id_product',$id_product)->firstOrFail();
        $data['related_product'] = Product::where('category_id',$data['product_detail']->category_id)->where('id_product','!=',$id_product)->orderBy('created_at','desc')->limit(8)->get();
        return view('frontend.modules.product.detail',$data);
    }
}
