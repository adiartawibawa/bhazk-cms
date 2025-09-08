<?php

namespace App\Providers;

use App\Services\IconService;
use App\Settings\AnalyticsSettings;
use App\Settings\AppearanceSettings;
use App\Settings\DeveloperSettings;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\MediaSettings;
use App\Settings\SeoSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IconService::class, function ($app) {
            return new IconService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        });

        // General Settings
        $general = app(GeneralSettings::class);
        Config::set('app.name', $general->site_name);
        Config::set('app.url', $general->site_url);
        Config::set('app.timezone', $general->timezone);

        // Developer Settings
        $developer = app(DeveloperSettings::class);
        Config::set('app.debug', $developer->debug_mode);

        // Language Settings
        $language = app(LanguageSettings::class);
        App::setLocale($language->default_language);

        // Media Settings
        $media = app(MediaSettings::class);
        Config::set('filesystems.default', $media->storage_disk);

        // Share ke semua Blade view
        View::share('generalSettings', $general);
        View::share('appearanceSettings', app(AppearanceSettings::class));
        View::share('seoSettings', app(SeoSettings::class));
        View::share('analyticsSettings', app(AnalyticsSettings::class));
    }
}
