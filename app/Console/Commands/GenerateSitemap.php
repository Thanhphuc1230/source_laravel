<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Danh mục sản phẩm
        $categories = DB::table('tp_cate_products')->select('id_cate_product', 'slug')->orderBy('stt', 'asc')->get();
        $products = DB::table('tp_products')->select('id_product', 'slug', 'category_id')->get();
        foreach ($categories as $cate) {
            $sitemap->add(Url::create('/category/'.$cate->slug.'.html')->setPriority(0.7));
            foreach ($products->where('category_id', $cate->id_cate_product) as $item) {
                $sitemap->add(Url::create('/'.$item->slug.'-'.$item->id_product.'.html')->setPriority(0.6));
            }
        }

        // Danh mục tin tức
        $newsCategories = DB::table('tp_cate_news')->select('id_cate_new', 'slug')->orderBy('stt', 'asc')->get();
        $newsList = DB::table('tp_news')->select('slug', 'id_new', 'category_id')->get();
        foreach ($newsCategories as $cate) {
            $sitemap->add(Url::create('/news/'.$cate->slug.'.html')->setPriority(0.5));
            foreach ($newsList->where('category_id', $cate->id_cate_new) as $item) {
                $sitemap->add(Url::create('/news/'.$cate->slug.'/'.$item->slug.'.html')->setPriority(0.4));
            }
        }

        // Trang nội dung
        $pages = DB::table('tp_pages')->select('slug')->get();
        foreach ($pages as $page) {
            $sitemap->add(Url::create('/page/'.$page->slug.'.html')->setPriority(0.5));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
