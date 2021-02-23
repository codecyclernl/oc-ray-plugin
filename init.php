<?php

use Codecycler\Ray\Classes\Ray as OctoberRay;
use Illuminate\Contracts\Container\BindingResolutionException;

function ocray(...$args)
{
    try {
        return app(OctoberRay::class)->send(...$args);
    } catch (BindingResolutionException $exception) {}
}