<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Database\Query;

use OtomatiesCoreVendor\Illuminate\Database\Grammar;
/** @internal */
interface Expression
{
    /**
     * Get the value of the expression.
     *
     * @param  \Illuminate\Database\Grammar  $grammar
     * @return string|int|float
     */
    public function getValue(Grammar $grammar);
}
