<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\Faq;
use App\Models\ShippingFaq;
use App\Models\CompanyFaq;
use App\Observers\ProductObserver;
use App\Observers\FaqObserver;
use App\Observers\ShippingFaqObserver;
use App\Observers\CompanyFaqObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
 Product::observe(ProductObserver::class);
 Faq::observe(FaqObserver::class);
 ShippingFaq::observe(ShippingFaqObserver::class);
  CompanyFaq::observe(CompanyFaqObserver::class);
    }
}
