<?php

namespace App\Providers;

use App\Services\DateTime\DateTimeManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register DateTimeManager as singleton
        $this->app->singleton(DateTimeManager::class, function ($app) {
            return new DateTimeManager();
        });
        
        // Alias for easier access
        $this->app->alias(DateTimeManager::class, 'datetime');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Tailwind CSS for pagination
        Paginator::useTailwind();
        
        // Blade directives using new DateTime system
        Blade::directive('dt', function ($expression) {
            return "<?php echo dt($expression)->toDateTimeString(); ?>";
        });
        
        Blade::directive('date', function ($expression) {
            return "<?php echo dt($expression)->toDateString(); ?>";
        });
        
        Blade::directive('time', function ($expression) {
            return "<?php echo dt($expression)->toTimeString(); ?>";
        });
        
        Blade::directive('relative', function ($expression) {
            return "<?php echo dt($expression)->toRelativeString(); ?>";
        });
        
        // Legacy blade directives for backward compatibility
        Blade::directive('timezone', function ($expression) {
            return "<?php echo dt($expression)->toDateTimeString(); ?>";
        });
        
        Blade::directive('timezoneDate', function ($expression) {
            return "<?php echo dt($expression)->toDateString(); ?>";
        });
        
        Blade::directive('timezoneTime', function ($expression) {
            return "<?php echo dt($expression)->toTimeString(); ?>";
        });
        
        Blade::directive('timezoneRelative', function ($expression) {
            return "<?php echo dt($expression)->toRelativeString(); ?>";
        });
    }
}
