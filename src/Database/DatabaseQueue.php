<?php

namespace Tochka\Queue\JobVisibility\Database;

use Illuminate\Queue\DatabaseQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CreatePayloadTrait;

class DatabaseQueue extends BaseQueue
{
    use CreatePayloadTrait;
}
