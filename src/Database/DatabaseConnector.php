<?php

namespace Tochka\Queue\JobVisibility\Database;

use Illuminate\Queue\Connectors\DatabaseConnector as BaseConnector;

class DatabaseConnector extends BaseConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array $config
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new DatabaseQueue(
            $this->connections->connection($config['connection'] ?? null),
            $config['table'],
            $config['queue'],
            $config['retry_after'] ?? 60
        );
    }
}
