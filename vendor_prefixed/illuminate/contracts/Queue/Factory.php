<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Queue;

/** @internal */
interface Factory
{
    /**
     * Resolve a queue connection instance.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connection($name = null);
}
