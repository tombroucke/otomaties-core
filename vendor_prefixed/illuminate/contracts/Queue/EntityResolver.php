<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Queue;

/** @internal */
interface EntityResolver
{
    /**
     * Resolve the entity for the given ID.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return mixed
     */
    public function resolve($type, $id);
}
