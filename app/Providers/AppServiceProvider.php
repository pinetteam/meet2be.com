<?php

namespace App\Providers;

use App\Services\TenantService;
use App\Services\TimezoneService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // TenantService'i singleton olarak kaydet
        $this->app->singleton(TenantService::class);
        
        // TimezoneService'i singleton olarak kaydet
        $this->app->singleton(TimezoneService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade Timezone Directives
        Blade::directive('timezone', function ($expression) {
            return "<?php 
                \$__date = $expression;
                if (\$__date instanceof \\Carbon\\Carbon || \$__date instanceof \\DateTime) {
                    echo app(App\Services\TimezoneService::class)->formatDateTime(\$__date);
                } elseif (is_string(\$__date) && !empty(\$__date)) {
                    echo app(App\Services\TimezoneService::class)->formatDateTime(\\Carbon\\Carbon::parse(\$__date));
                } else {
                    echo '';
                }
            ?>";
        });
        
        Blade::directive('timezoneDate', function ($expression) {
            return "<?php 
                \$__date = $expression;
                if (\$__date instanceof \\Carbon\\Carbon || \$__date instanceof \\DateTime) {
                    echo app(App\Services\TimezoneService::class)->formatDate(\$__date);
                } elseif (is_string(\$__date) && !empty(\$__date)) {
                    echo app(App\Services\TimezoneService::class)->formatDate(\\Carbon\\Carbon::parse(\$__date));
                } else {
                    echo '';
                }
            ?>";
        });
        
        Blade::directive('timezoneTime', function ($expression) {
            return "<?php 
                \$__date = $expression;
                if (\$__date instanceof \\Carbon\\Carbon || \$__date instanceof \\DateTime) {
                    echo app(App\Services\TimezoneService::class)->formatTime(\$__date);
                } elseif (is_string(\$__date) && !empty(\$__date)) {
                    echo app(App\Services\TimezoneService::class)->formatTime(\\Carbon\\Carbon::parse(\$__date));
                } else {
                    echo '';
                }
            ?>";
        });
        
        Blade::directive('timezoneRelative', function ($expression) {
            return "<?php 
                \$__date = $expression;
                if (\$__date instanceof \\Carbon\\Carbon || \$__date instanceof \\DateTime) {
                    echo app(App\Services\TimezoneService::class)->formatRelative(\$__date);
                } elseif (is_string(\$__date) && !empty(\$__date)) {
                    echo app(App\Services\TimezoneService::class)->formatRelative(\\Carbon\\Carbon::parse(\$__date));
                } else {
                    echo '';
                }
            ?>";
        });
    }
}
