<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Validation;

use Closure;
/**
 * @deprecated see ValidationRule
 * @internal
 */
interface InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke(string $attribute, mixed $value, Closure $fail);
}
