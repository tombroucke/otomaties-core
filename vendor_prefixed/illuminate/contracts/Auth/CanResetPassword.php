<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Auth;

/** @internal */
interface CanResetPassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset();
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token);
}
