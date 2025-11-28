<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Broadcasting;

/** @internal */
interface ShouldBroadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]|string[]|string
     */
    public function broadcastOn();
}
