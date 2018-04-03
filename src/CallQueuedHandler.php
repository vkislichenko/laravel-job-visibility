<?php

namespace Tochka\Queue\JobVisibility;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\CallQueuedHandler as BaseHandler;
use Tochka\Queue\JobVisibility\Contracts\JobReturner;

class CallQueuedHandler extends BaseHandler implements JobReturner
{

    protected $command;

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
            $this->command = $this->setJobInstanceIfNecessary(
                $job, unserialize($data['command'])
            );
        } catch (ModelNotFoundException $e) {
            return $this->handleModelNotFound($job, $e);
        }

        $this->dispatcher->dispatchNow(
            $this->command, $this->resolveHandler($job, $this->command)
        );

        if (!$job->hasFailed() && !$job->isReleased()) {
            $this->ensureNextJobInChainIsDispatched($this->command);
        }

        if (!$job->isDeletedOrReleased()) {
            $job->delete();
        }
    }

    public function getJob()
    {
        return $this->command;
    }
}
