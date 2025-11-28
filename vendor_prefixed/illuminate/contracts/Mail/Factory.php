<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Mail;

/** @internal */
interface Factory
{
    /**
     * Get a mailer instance by name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Mail\Mailer
     */
    public function mailer($name = null);
}
