<?php

namespace Livestream\Streaming;

use Illuminate\Support\ServiceProvider;

class LiveStreamServiceProvider extends ServiceProvider{
    public function boot(){
        $this->publishes([
            __DIR__.'/config/livestream.php' => config_path('livestream.php')
        ], 'livestream-config');
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'livestream-migration');

        if(file_exists(__DIR__.'/Helper/helper.php')){
            include_once(__DIR__.'/Helper/helper.php');
        }
    }

    public function register(){
        $this->app->singleton(LiveStream::class, function(){
            return new LiveStream();
        });
    }
}