<?php

namespace Tochka\Queue\JobVisibility;

trait CreatePayloadTrait
{
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