<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Menu\MenuChanged;
use App\Listeners\Menu\ClearMenuCache;
use App\Events\Feedback\FeedbackChanged;
use App\Listeners\Feedback\ClearFeedbackCache;
use App\Events\Slider\SliderChanged;
use App\Listeners\Slider\ClearSliderCache;
use App\Events\News\NewsChanged;
use App\Listeners\News\ClearNewsCache;
use App\Events\Page\PageChanged;
use App\Listeners\Page\ClearPageCache;
use App\Events\CateProduct\CateProductChanged;
use App\Listeners\CateProduct\ClearCateProductCache;
use App\Events\CateNew\CateNewChanged;
use App\Listeners\CateNew\ClearCateNewCache;
use App\Events\Product\ProductChanged;
use App\Listeners\Product\ClearProductCache;

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
