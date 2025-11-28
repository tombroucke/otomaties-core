<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Auth;

/** @internal */
interface PasswordBrokerFactory
{
    /**
     * Get a password broker instance by name.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null);
}
