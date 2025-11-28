<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Container;

use Exception;
use OtomatiesCoreVendor\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
