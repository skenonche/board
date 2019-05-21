<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Imgur\Client;

class ImgurServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $client = new Client();
            $client->setOption('client_id', $app['config']->get('imgur.client_id'));
            $client->setOption('client_secret', $app['config']->get('imgur.client_secret'));

            return $client;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
