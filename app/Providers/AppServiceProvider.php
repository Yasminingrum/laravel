<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Collections\ProductCollection;
use Illuminate\Support\Facades\Blade;

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
        // ========== PAGINATION CONFIGURATION ==========

        // Use custom pagination views
        Paginator::defaultView('pagination.custom');
        Paginator::defaultSimpleView('pagination.simple-custom');

        // ========== ELOQUENT CONFIGURATION ==========

        // Prevent lazy loading in development
        if ($this->app->environment('local', 'development')) {
            Model::preventLazyLoading();
        }

        // ========== CUSTOM COLLECTION BINDING ==========

        // This is already handled in Product model newCollection method
        // But we can add global configuration here if needed

        // ========== GLOBAL VIEW COMPOSERS ==========

        // Share categories with navigation views
        view()->composer(['components.template'], function ($view) {
            $categories = \App\Models\Category::active()->ordered()->take(10)->get();
            $view->with('globalCategories', $categories);
        });

        // ========== URL CONFIGURATION ==========

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // ========== VALIDATION CUSTOM RULES ==========

        // Custom validation for Indonesian phone numbers (example)
        \Illuminate\Support\Facades\Validator::extend('indonesian_phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(\+62|62|0)[2-9]{1}[0-9]{1,3}[0-9]{6,8}$/', $value);
        });

        // ========== CUSTOM BLADE DIRECTIVES ==========

        // Custom directive for Indonesian currency formatting
        \Illuminate\Support\Facades\Blade::directive('currency', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });

        // Custom directive for stock status badge
        \Illuminate\Support\Facades\Blade::directive('stockBadge', function ($expression) {
            return "<?php
                \$stock = $expression;
                if (\$stock <= 0) {
                    echo '<span class=\"badge bg-danger\">Out of Stock</span>';
                } elseif (\$stock <= 5) {
                    echo '<span class=\"badge bg-warning\">Low Stock (' . \$stock . ')</span>';
                } else {
                    echo '<span class=\"badge bg-success\">In Stock (' . \$stock . ')</span>';
                }
            ?>";
        });
    }
}
