<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\Symfony\Component\Translation\Exception;

/**
 * Thrown when a resource cannot be loaded.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
class InvalidResourceException extends \InvalidArgumentException implements ExceptionInterface
{
}
