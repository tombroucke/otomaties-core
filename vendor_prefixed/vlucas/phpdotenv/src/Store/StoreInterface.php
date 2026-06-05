<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Dotenv\Store;

/** @internal */
interface StoreInterface
{
    /**
     * Read the content of the environment file(s).
     *
     * @throws \Dotenv\Exception\InvalidEncodingException|\Dotenv\Exception\InvalidPathException
     *
     * @return string
     */
    public function read();
}
