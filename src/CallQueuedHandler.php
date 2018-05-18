<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\CallQueuedHandler as BaseHandler;

class CallQueuedHandler extends BaseHandler
{
    /**
     * Handle the queued job.
     *
     * @param  \Illuminate\Contracts\Queue\Job $job
     * @param  array                           $data
     *
     * @return void
     */
    public function call(Job $job, array $data)
    {
        try {
            $command = $this->setJobInstanceIfNecessary(
                $job, unserialize($data['command'])
            );
        } catch (ModelNotFoundException $e) {
            return $this->handleModelNotFound($job, $e);
        }

        try {
            $this->dispatcher->dispatchNow($command, $this->resolveHandler($job, $command));
        } catch (\Exception $e) {
            throw $e;
        } finally {
            app()->instance('QueueCurrentJob', $command);
        }

        if (!$job->hasFailed() && !$job->isReleased()) {
            $this->ensureNextJobInChainIsDispatched($command);
        }

        if (!$job->isDeletedOrReleased()) {
            $job->delete();
        }
    }
}
