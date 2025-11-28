<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Support;

/** @internal */
interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
