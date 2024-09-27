<?php

// Hiển thị views ở muôn nơi (chắc thế)

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

// Khai báo language repository để lấy dữ liệu
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

class LanguageComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Repositories\Interfaces\LanguageRepositoryInterface', 'App\Repositories\LanguageRepository');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Đổ biến language vào để dùng chung cho các trang
        View::composer('backend.dashboard.layout', function($view) {
            $languageRepository = $this->app->make(LanguageRepository::class);
            $languages = $languageRepository->all();
            $view->with('languages', $languages);
        });
    }
}
