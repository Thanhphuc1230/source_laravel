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
        // Cache category data
        $data['category_detail'] = cache()->remember(
            "category_detail_{$slug_cate_product}", 
            3600, 
            fn() => CateProduct::where('status',1)->where('slug',$slug_cate_product)->firstOrFail()
        );
        
        // Cache category list
        $data['category_product'] = cache()->remember(
            'category_product_home', 
            3600, 
            fn() => CateProduct::where('status',1)->where('home',1)->orderBy('stt','asc')->where('parent_id',0)->get()
        );

        $id_category_child = CateProduct::where('parent_id',$data['category_detail']->id_cate_product)->pluck('id_cate_product');
        $id_category_child = $id_category_child->merge([$data['category_detail']->id_cate_product]);
        
        // Optimized query with select specific fields
        $data['products'] = Product::with(['cate:id_cate_product,name_vn,slug'])
            ->select('id_product', 'uuid', 'name_vn', 'slug', 'price', 'price_old', 'image', 'intro_vn', 'category_id', 'status', 'stt')
            ->whereIn('category_id',$id_category_child)
            ->where('status',1)
        ->orderBy('stt','asc')
            ->paginate(16)
            ->withQueryString();
        
        return view('frontend.modules.product.category',$data);
    }

    public function detailProduct($slug_product)
    {
        // Cache product detail
        $data['product_detail'] = cache()->remember(
            "product_detail_{$slug_product}", 
            3600, 
            fn() => Product::with(['cate:id_cate_product,name_vn,slug'])
                ->where('slug',$slug_product)
                ->firstOrFail()
        );
        
        // Optimized related products query
        $data['related_product'] = Product::select('id_product', 'uuid', 'name_vn', 'slug', 'price', 'price_old', 'image', 'intro_vn')
            ->where('category_id',$data['product_detail']->category_id)
            ->where('id_product','!=',$data['product_detail']->id_product)
            ->where('status', 1)
            ->orderBy('created_at','desc')
            ->limit(8)
            ->get();
            
        return view('frontend.modules.product.detail',$data);
    }
}
