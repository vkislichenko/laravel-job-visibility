<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Tochka\Queue\JobVisibility\Database\DatabaseConnector;
use Tochka\Queue\JobVisibility\Horizon\RedisConnector as HorizonConnector;
use Tochka\Queue\JobVisibility\RabbitMQ\RabbitMQConnector;
use Tochka\Queue\JobVisibility\Redis\RedisConnector;

class JobVisibilityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->booted(function() {
            $this->bootAfterAll();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function bootAfterAll()
    {
        /** @var QueueManager $queue */
        $queue = app('queue');

        // Add database Connector
        $queue->addConnector('database', function () {
            return new DatabaseConnector($this->app['db']);
        });

        // Add RabbitMq Connector
        if (!empty($this->app->getProviders('VladimirYuldashev\LaravelQueueRabbitMQ\LaravelQueueRabbitMQServiceProvider'))) {
            $queue->addConnector('rabbitmq', function () {
                return new RabbitMQConnector($this->app['events']);
            });
        }

        if (!empty($this->app->getProviders('Laravel\Horizon\HorizonServiceProvider'))) {
            // Add horizon Connector
            $queue->addConnector('redis', function () {
                return new HorizonConnector($this->app['redis']);
            });
        } else {
            // Add Redis Connector
            $queue->addConnector('redis', function () {
                return new RedisConnector($this->app['redis']);
            });
        }
    }
}
