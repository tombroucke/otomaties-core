<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Validation;

/**
 * @deprecated see ValidationRule
 * @internal
 */
interface Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value);
    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message();
}
