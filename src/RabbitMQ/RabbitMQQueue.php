<?php

namespace Tochka\Queue\JobVisibility\RabbitMQ;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CreatePayloadTrait;

class RabbitMQQueue extends BaseQueue
{
    use CreatePayloadTrait;
}
