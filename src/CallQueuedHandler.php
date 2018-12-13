<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\CallQueuedHandler as BaseHandler;
use Tochka\Queue\JobVisibility\Contracts\ErrorHandler;

class CallQueuedHandler extends BaseHandler
{
    /**
     * Handle the queued job.
     *
     * @param  \Illuminate\Contracts\Queue\Job $job
     * @param  array $data
     *
     * @return void
     * @throws \Exception
     */
    public function call(Job $job, array $data)
    {
        try {
            $command = $this->setJobInstanceIfNecessary(
                $job, unserialize($data['command'])
            );
        } catch (ModelNotFoundException $e) {
            $this->handleModelNotFound($job, $e);
            return;
        }

        try {
            $this->dispatcher->dispatchNow($command, $this->resolveHandler($job, $command));
        } catch (\Exception $e) {
            if ($command instanceof ErrorHandler) {
                $command->errorHandle($e);
            }

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
