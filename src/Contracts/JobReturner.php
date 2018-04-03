<?php

namespace Tochka\Queue\JobVisibility\Contracts;

interface JobReturner
{
    public function getJob();
}