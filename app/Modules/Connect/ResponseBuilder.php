<?php

namespace Otomaties\Core\Modules\Connect;

use Otomaties\Core\Illuminate\Support\Str;

/**
 * Response builder
 *
 * @method env(mixed $arguments = [])
 * @method version(mixed $arguments = [])
 * @method plugins(mixed $arguments = [])
 * @method reading(mixed $arguments = [])
 * @method security(mixed $arguments = [])
 * @method woocommerce(mixed $arguments = [])
 */
class ResponseBuilder
{
    /**
     * The response array
     *
     * @var array<string, mixed>
     */
    private array $response = [];

    /**
     * Magic method to set and get response items
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $name, array $arguments): ResponseBuilder|ResponseBuilderItem
    {
        $name = Str::snake($name);

        if (count($arguments) == 1) {
            $this->response[$name] = $arguments[0];

            return $this;
        }

        if (! isset($this->response[$name])) {
            $this->response[$name] = new ResponseBuilderItem($this);
        }

        return $this->response[$name] instanceof ResponseBuilderItem ? $this->response[$name] : $this;
    }

    /**
     * Build the response array
     *
     * @return array<string, mixed>
     */
    public function build(): array
    {
        // convert all ResponseBuilderItem objects to arrays recursively
        array_walk_recursive($this->response, function (&$item) {
            if ($item instanceof ResponseBuilderItem) {
                $item = $item->toArray();
            }
        });

        return $this->response;
    }
}
