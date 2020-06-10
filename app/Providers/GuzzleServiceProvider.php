<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class GuzzleServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $remoteUrl = env('REMOTE_URL');
        $remoteUser = env('REMOTE_USER');
        $remoteKey = env('REMOTE_KEY');
        $this->app->bind('GuzzleHttp\Client', function($api) use ($remoteUrl, $remoteUser, $remoteKey) {
            return new Client([
                'base_uri' => $remoteUrl,
                'auth' => [$remoteUser, $remoteKey],
                'headers' => ['content-type' => 'application/json; charset=utf-8']
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
