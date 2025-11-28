<?php

namespace OtomatiesCoreVendor\Illuminate\Contracts\Support;

/** @internal */
interface Htmlable
{
    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml();
}
