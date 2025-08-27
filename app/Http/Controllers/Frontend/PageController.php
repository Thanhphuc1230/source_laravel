<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function page($slug_page)
    {
        $data['page_detail'] = Page::where('slug', $slug_page)->firstOrFail();

        return view('frontend.modules.page.detail', $data);
    }
}
