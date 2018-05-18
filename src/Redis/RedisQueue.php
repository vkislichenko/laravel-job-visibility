<?php

namespace Tochka\Queue\JobVisibility\Redis;

use Illuminate\Queue\RedisQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CreatePayloadTrait;

class RedisQueue extends BaseQueue
{
    use CreatePayloadTrait;
}
