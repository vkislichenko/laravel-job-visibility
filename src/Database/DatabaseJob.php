<?php

namespace Tochka\Queue\JobVisibility\Database;

use Illuminate\Queue\Jobs\DatabaseJob as BaseJob;
use Tochka\Queue\JobVisibility\Contracts\InstanceReturner;

class DatabaseJob extends BaseJob implements InstanceReturner
{
    public function getInstance()
    {
        return $this->instance;
    }
}
