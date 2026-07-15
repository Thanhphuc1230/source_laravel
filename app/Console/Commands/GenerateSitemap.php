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

        // Add home page
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // 1. Category Product (Danh mục sản phẩm)
        $categories = DB::table('tp_cate_products')
            ->where('status', 1)
            ->select('slug_vn', 'slug_en')
            ->get();
        foreach ($categories as $cate) {
            if ($cate->slug_vn && $cate->slug_en) {
                $sitemap->add(Url::create('/' . $cate->slug_vn . '.html')
                    ->setPriority(0.8)
                    ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $cate->slug_en . '.html', 'en'));
                $sitemap->add(Url::create('/' . $cate->slug_en . '.html')
                    ->setPriority(0.8)
                    ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $cate->slug_en . '.html', 'en'));
            } else {
                if ($cate->slug_vn) {
                    $sitemap->add(Url::create('/' . $cate->slug_vn . '.html')->setPriority(0.8));
                }
                if ($cate->slug_en) {
                    $sitemap->add(Url::create('/' . $cate->slug_en . '.html')->setPriority(0.8));
                }
            }
        }

        // 2. Product Detail (Chi tiết sản phẩm/tour)
        $products = DB::table('tp_products')
            ->where('status', 1)
            ->select('slug_vn', 'slug_en')
            ->get();
        foreach ($products as $prod) {
            if ($prod->slug_vn && $prod->slug_en) {
                $sitemap->add(Url::create('/' . $prod->slug_vn . '.html')
                    ->setPriority(0.7)
                    ->addAlternate('/' . $prod->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $prod->slug_en . '.html', 'en'));
                $sitemap->add(Url::create('/' . $prod->slug_en . '.html')
                    ->setPriority(0.7)
                    ->addAlternate('/' . $prod->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $prod->slug_en . '.html', 'en'));
            } else {
                if ($prod->slug_vn) {
                    $sitemap->add(Url::create('/' . $prod->slug_vn . '.html')->setPriority(0.7));
                }
                if ($prod->slug_en) {
                    $sitemap->add(Url::create('/' . $prod->slug_en . '.html')->setPriority(0.7));
                }
            }
        }

        // 3. Category News (Danh mục tin tức)
        $newsCategories = DB::table('tp_cate_news')
            ->where('status', 1)
            ->select('slug_vn', 'slug_en')
            ->get();
        foreach ($newsCategories as $cate) {
            if ($cate->slug_vn && $cate->slug_en) {
                $sitemap->add(Url::create('/' . $cate->slug_vn . '.html')
                    ->setPriority(0.6)
                    ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $cate->slug_en . '.html', 'en'));
                $sitemap->add(Url::create('/' . $cate->slug_en . '.html')
                    ->setPriority(0.6)
                    ->addAlternate('/' . $cate->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $cate->slug_en . '.html', 'en'));
            } else {
                if ($cate->slug_vn) {
                    $sitemap->add(Url::create('/' . $cate->slug_vn . '.html')->setPriority(0.6));
                }
                if ($cate->slug_en) {
                    $sitemap->add(Url::create('/' . $cate->slug_en . '.html')->setPriority(0.6));
                }
            }
        }

        // 4. News Detail (Chi tiết bài viết)
        $newsList = DB::table('tp_news')
            ->where('status', 1)
            ->select('slug_vn', 'slug_en')
            ->get();
        foreach ($newsList as $news) {
            if ($news->slug_vn && $news->slug_en) {
                $sitemap->add(Url::create('/' . $news->slug_vn . '.html')
                    ->setPriority(0.6)
                    ->addAlternate('/' . $news->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $news->slug_en . '.html', 'en'));
                $sitemap->add(Url::create('/' . $news->slug_en . '.html')
                    ->setPriority(0.6)
                    ->addAlternate('/' . $news->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $news->slug_en . '.html', 'en'));
            } else {
                if ($news->slug_vn) {
                    $sitemap->add(Url::create('/' . $news->slug_vn . '.html')->setPriority(0.6));
                }
                if ($news->slug_en) {
                    $sitemap->add(Url::create('/' . $news->slug_en . '.html')->setPriority(0.6));
                }
            }
        }

        // 5. Pages (Trang nội dung tĩnh)
        $pages = DB::table('tp_pages')
            ->where('status', 1)
            ->select('slug_vn', 'slug_en')
            ->get();
        foreach ($pages as $page) {
            if ($page->slug_vn && $page->slug_en) {
                $sitemap->add(Url::create('/' . $page->slug_vn . '.html')
                    ->setPriority(0.5)
                    ->addAlternate('/' . $page->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $page->slug_en . '.html', 'en'));
                $sitemap->add(Url::create('/' . $page->slug_en . '.html')
                    ->setPriority(0.5)
                    ->addAlternate('/' . $page->slug_vn . '.html', 'vi')
                    ->addAlternate('/' . $page->slug_en . '.html', 'en'));
            } else {
                if ($page->slug_vn) {
                    $sitemap->add(Url::create('/' . $page->slug_vn . '.html')->setPriority(0.5));
                }
                if ($page->slug_en) {
                    $sitemap->add(Url::create('/' . $page->slug_en . '.html')->setPriority(0.5));
                }
            }
        }

        // Add static contact page
        $sitemap->add(Url::create('/lien-he.html')->setPriority(0.4));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
