<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));
        $presence = 0;

        View::composer(['app', 'layouts/app', 'layouts/admin'], function ($view) use ($presence) {
            if (user()) {
                $view
                    ->with('notifications_count', user()->unreadNotifications->count())
                    ->with('private_unread_count', \App\Models\Discussion::private(user())->count() - \App\Models\Discussion::private(user())->read(user())->count());
            }

            $view
                ->with('presence_counter', $presence)
                ->with('body_classes', 'theme-4sucres');

            return $view;
        });

        Inertia::share([
            'app' => [
                'name'             => Config::get('app.name'),
                'version'          => app(\PragmaRX\Version\Package\Version::class)->format('compact'),
                'runtime'          => round((microtime(true) - LARAVEL_START), 3),
                'real_runtime'     => function () { return round((microtime(true) - LARAVEL_START), 3); },
                'presence'         => function () { return User::active()->count(); },
            ],
            'auth' => function () {
                return [
                    'user' => user() ? [
                        'id'            => user()->id,
                        'display_name'  => user()->display_name,
                        'avatar_link'   => user()->avatar_link,
                        'roles'         => user()->roles->pluck('name'),
                    ] : null,
                ];
            },
        ]);

        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
