<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SitemapController extends Controller
{
    public function generate(Request $request)
    {
        set_time_limit(300); // 300 giây = 5 phút
        Artisan::call('sitemap:generate');
        return response()->json(['message' => 'Sitemap đã được tạo thành công!']);
    }
}
