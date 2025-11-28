<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Support;

/**
 * @template TKey of array-key
 * @template TValue
 * @internal
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray();
}
