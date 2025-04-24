<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CateProduct;
use App\Models\Product;
use App\Models\Slider;
class HomeController extends Controller
{
    public function home()
    {
        $data['sliders'] = Slider::where('status', 1)->orderBy('stt', 'asc')->get();
        return view('frontend.modules.home.index', $data);
    }

   
}
