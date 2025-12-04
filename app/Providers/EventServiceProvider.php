<?php

namespace App\Providers;

use App\Events\Brand\BrandChanged;
use App\Events\CateNew\CateNewChanged;
use App\Events\CateProduct\CateProductChanged;
use App\Events\Feature\FeatureChanged;
use App\Events\Gallery\GalleryChanged;
use App\Events\Feedback\FeedbackChanged;
use App\Events\Menu\MenuChanged;
use App\Events\News\NewsChanged;
use App\Events\Page\PageChanged;
use App\Events\Product\ProductChanged;
use App\Events\Slider\SliderChanged;
use App\Listeners\Brand\ClearBrandCache;
use App\Listeners\CateNew\ClearCateNewCache;
use App\Listeners\CateProduct\ClearCateProductCache;
use App\Listeners\Feature\ClearFeatureCache;
use App\Listeners\Gallery\ClearGalleryCache;
use App\Listeners\Feedback\ClearFeedbackCache;
use App\Listeners\Menu\ClearMenuCache;
use App\Listeners\News\ClearNewsCache;
use App\Listeners\Page\ClearPageCache;
use App\Listeners\Product\ClearProductCache;
use App\Listeners\Slider\ClearSliderCache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Menu Events
        MenuChanged::class => [
            ClearMenuCache::class,
        ],

        // Feedback Events
        FeedbackChanged::class => [
            ClearFeedbackCache::class,
        ],

        // Slider Events
        SliderChanged::class => [
            ClearSliderCache::class,
        ],

        // News Events
        NewsChanged::class => [
            ClearNewsCache::class,
        ],

        // Page Events
        PageChanged::class => [
            ClearPageCache::class,
        ],

        // CateProduct Events
        CateProductChanged::class => [
            ClearCateProductCache::class,
        ],

        // CateNew Events
        CateNewChanged::class => [
            ClearCateNewCache::class,
        ],

        // Product Events
        ProductChanged::class => [
            ClearProductCache::class,
        ],

        // Brand Events
        BrandChanged::class => [
            ClearBrandCache::class,
        ],

        // Feature Events
        FeatureChanged::class => [
            ClearFeatureCache::class,
        ],

        // Gallery Events
        GalleryChanged::class => [
            ClearGalleryCache::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
