<?php namespace League\Tiny;

use Illuminate\Support\ServiceProvider;

class TinyServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app['tiny.generate'] = $this->app->share(function ($app) {
            return new TinyGenerateCommand($app['files']);
        });

        $this->commands('tiny.generate');
    }

    public function register()
    {
        $this->app['tiny'] = $this->app->share(function ($app) {
            $key = getenv('LEAGUE_TINY_KEY');

            return new Tiny($key);
        });
    }

    public function provides()
    {
        return array('tiny');
    }

}
