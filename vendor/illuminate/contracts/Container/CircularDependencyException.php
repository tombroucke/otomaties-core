<?php

namespace Otomaties\Core\Illuminate\Contracts\Container;

use Exception;
use Otomaties\Core\Psr\Container\ContainerExceptionInterface;
class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
