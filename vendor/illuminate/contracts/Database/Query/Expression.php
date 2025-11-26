<?php

namespace Otomaties\Core\Illuminate\Contracts\Database\Query;

use Otomaties\Core\Illuminate\Database\Grammar;
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
