<?php

namespace Tochka\Queue\JobVisibility\Horizon;

use Laravel\Horizon\RedisQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CreatePayloadTrait;

class RedisQueue extends BaseQueue
{
    use CreatePayloadTrait;
}
