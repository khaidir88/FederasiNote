<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        // View::composer('*', function ($view) {
        //     $categories = Category::with('children')
        //         ->whereNull('parent_id')
        //         ->where('is_active', 1)
        //         ->get();

        //     $view->with('navCategories', $categories);
        // });
        View::composer('*', function ($view) {
            $categories = Category::with([
                'children' => function ($q) {
                    $q->where('is_active', 1);
                }
            ])
                ->whereNull('parent_id')
                ->where('is_active', 1)
                ->get();

            $view->with('navCategories', $categories);
        });
    }
}
