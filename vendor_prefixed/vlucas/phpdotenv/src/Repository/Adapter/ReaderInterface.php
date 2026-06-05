<?php

declare (strict_types=1);
namespace OtomatiesCoreVendor\Dotenv\Repository\Adapter;

/** @internal */
interface ReaderInterface
{
    /**
     * Read an environment variable, if it exists.
     *
     * @param non-empty-string $name
     *
     * @return \PhpOption\Option<string>
     */
    public function read(string $name);
}
