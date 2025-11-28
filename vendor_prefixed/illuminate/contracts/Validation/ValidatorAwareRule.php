<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Validation;

use OtomatiesCoreVendor\Illuminate\Validation\Validator;
interface ValidatorAwareRule
{
    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator);
}
