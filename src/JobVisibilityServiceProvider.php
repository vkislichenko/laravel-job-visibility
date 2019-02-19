<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Queue\Queue;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Tochka\Queue\JobVisibility\Database\DatabaseConnector;
use Tochka\Queue\JobVisibility\Horizon\RedisConnector as HorizonConnector;
use Tochka\Queue\JobVisibility\RabbitMQ\RabbitMQConnector;
use Tochka\Queue\JobVisibility\Redis\RedisConnector;

class JobVisibilityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (version_compare(app()->version(), '5.7.7', '>=')) {
            Queue::createPayloadUsing(function ($connection, $queue, $payload) {
                if (isset($payload['job'])) {
                    return ['job' => CallQueuedHandler::class . '@call'];
                }

                return [];
            });
        } else {
            $this->app->booted(function () {
                $this->bootAfterAll();
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function bootAfterAll(): void
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
