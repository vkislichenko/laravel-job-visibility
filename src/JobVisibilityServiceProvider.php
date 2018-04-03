<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Tochka\Queue\JobVisibility\Database\DatabaseConnector;

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
    }
}
