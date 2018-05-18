<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Tochka\Queue\JobVisibility\Database\DatabaseConnector;
use Tochka\Queue\JobVisibility\Horizon\RedisConnector as HorizonConnector;
use Tochka\Queue\JobVisibility\Redis\RedisConnector;

class JobVisibilityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var QueueManager $queue */
        $queue = app('queue');

        $queue->addConnector('database', function () {
            return new DatabaseConnector($this->app['db']);
        });

        if (!empty(App::getProviders('Laravel\Horizon\HorizonServiceProvider'))) {
            $queue->addConnector('redis', function () {
                return new HorizonConnector($this->app['redis']);
            });
        } else {
            $queue->addConnector('redis', function () {
                return new RedisConnector($this->app['redis']);
            });
        }
    }
}
