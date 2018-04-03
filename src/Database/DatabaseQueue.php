<?php

namespace Tochka\Queue\JobVisibility\Database;

use Illuminate\Queue\DatabaseQueue as BaseQueue;
use Tochka\Queue\JobVisibility\CallQueuedHandler;

class DatabaseQueue extends BaseQueue
{
    /**
     * Marshal the reserved job into a DatabaseJob instance.
     *
     * @param  string                                   $queue
     * @param  \Illuminate\Queue\Jobs\DatabaseJobRecord $job
     *
     * @return DatabaseJob
     */
    protected function marshalJob($queue, $job): DatabaseJob
    {
        $job = $this->markJobAsReserved($job);

        $this->database->commit();

        return new DatabaseJob(
            $this->container, $this, $job, $this->connectionName, $queue
        );
    }

    /**
     * Create a payload for an object-based queue handler.
     *
     * @param  mixed $job
     *
     * @return array
     */
    protected function createObjectPayload($job): array
    {
        $payload = parent::createObjectPayload($job);
        $payload['job'] = CallQueuedHandler::class . '@call';

        return $payload;
    }

}
