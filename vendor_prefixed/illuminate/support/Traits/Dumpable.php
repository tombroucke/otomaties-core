<?php

namespace OtomatiesCoreVendor\Illuminate\Support\Traits;

/** @internal */
trait Dumpable
{
    /**
     * Dump the given arguments and terminate execution.
     *
     * @param  mixed  ...$args
     * @return never
     */
    public function dd(...$args)
    {
        dd($this, ...$args);
    }
    /**
     * Dump the given arguments.
     *
     * @param  mixed  ...$args
     * @return $this
     */
    public function dump(...$args)
    {
        dump($this, ...$args);
        return $this;
    }
}
