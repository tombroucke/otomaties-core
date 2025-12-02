<?php

namespace Otomaties\Core\Modules\Connect;

use OtomatiesCoreVendor\Illuminate\Support\Str;

/**
 * Response builder item
 */
#[\AllowDynamicProperties]
class ResponseBuilderItem
{
    /**
     * Set the parent object
     */
    public function __construct(private ResponseBuilderItem|ResponseBuilder $parent)
    {
        //
    }

    /**
     * Magic method to set and get response items
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $name, array $arguments): self|ResponseBuilder
    {
        $name = Str::snake($name);

        if (mb_substr($name, 0, 4) === 'end_') {
            return $this->end();
        }
        if (count($arguments) === 1) {
            $this->$name = $arguments[0];
        } else {
            $this->$name = new self($this);

            return $this->$name;
        }

        return $this;
    }

    /**
     * Build the response array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $response = get_object_vars($this);
        unset($response['parent']);
        foreach ($response as $key => $value) {
            if ($value instanceof self) {
                $response[$key] = $value->toArray();
            }
        }

        return $response;
    }

    /**
     * End the current item and return the parent
     */
    public function end(): self|ResponseBuilder
    {
        return $this->parent;
    }
}
