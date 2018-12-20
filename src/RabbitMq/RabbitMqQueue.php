<?php

namespace Tochka\Queue\JobVisibility\RabbitMq;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CreatePayloadTrait;

class RabbitMqQueue extends BaseQueue
{
    use CreatePayloadTrait;
}
