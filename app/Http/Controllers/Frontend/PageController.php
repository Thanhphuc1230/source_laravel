<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function page($id_page)
    {
        $data['page_detail'] = Page::where('id_page', $id_page)->firstOrFail();

        return view('frontend.modules.page.index', $data);
    }
}
