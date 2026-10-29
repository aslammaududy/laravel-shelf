<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\ServiceProvider;

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
        AnonymousResourceCollection::macro('paginationInformation', function ($request, $paginated, $default) {
            $default['meta']['page'] = $default['meta']['current_page'];
            $default['meta']['total_pages'] = $default['meta']['last_page'];

            unset($default['meta']['current_page']);
            unset($default['meta']['last_page']);

            return $default;
        });
    }
}
