<?php

declare (strict_types=1);
/*
 * This file is part of Result Type.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OtomatiesCoreVendor\GrahamCampbell\ResultType;

/**
 * @template T
 * @template E
 * @internal
 */
abstract class Result
{
    /**
     * Get the success option value.
     *
     * @return \PhpOption\Option<T>
     */
    public abstract function success();
    /**
     * Map over the success value.
     *
     * @template S
     *
     * @param callable(T):S $f
     *
     * @return \GrahamCampbell\ResultType\Result<S,E>
     */
    public abstract function map(callable $f);
    /**
     * Flat map over the success value.
     *
     * @template S
     * @template F
     *
     * @param callable(T):\GrahamCampbell\ResultType\Result<S,F> $f
     *
     * @return \GrahamCampbell\ResultType\Result<S,F>
     */
    public abstract function flatMap(callable $f);
    /**
     * Get the error option value.
     *
     * @return \PhpOption\Option<E>
     */
    public abstract function error();
    /**
     * Map over the error value.
     *
     * @template F
     *
     * @param callable(E):F $f
     *
     * @return \GrahamCampbell\ResultType\Result<T,F>
     */
    public abstract function mapError(callable $f);
}
