<?php

namespace Tochka\Queue\JobVisibility\Contracts;

interface ErrorHandler
{
    public function errorHandle(\Exception $e);
}