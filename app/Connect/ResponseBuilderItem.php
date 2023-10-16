<?php
namespace Otomaties\Core\Connect;

use Otomaties\Core\Helpers\Str;

/**
 * Response builder item
 */
class ResponseBuilderItem
{
    /**
     * Set the parent object
     *
     * @param ResponseBuilderItem|ResponseBuilder $parent
     */
    public function __construct(private ResponseBuilderItem|ResponseBuilder $parent)
    {
    }

    /**
     * Magic method to set and get response items
     *
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return ResponseBuilderItem|ResponseBuilder
     */
    public function __call(string $name, array $arguments) : ResponseBuilderItem|ResponseBuilder
    {
        $name = Str::snake($name);
        
        if (substr($name, 0, 4) == 'end_') {
            return $this->end();
        }
        if (count($arguments) == 1) {
            $this->$name = $arguments[0];
        } else {
            $this->$name = new ResponseBuilderItem($this);
            return $this->$name;
        }
        return $this;
    }

    /**
     * Build the response array
     *
     * @return array<string, mixed>
     */
    public function toArray() : array
    {
        $response = get_object_vars($this);
        unset($response['parent']);
        foreach ($response as $key => $value) {
            if ($value instanceof ResponseBuilderItem) {
                $response[$key] = $value->toArray();
            }
        }
        return $response;
    }

    /**
     * End the current item and return the parent
     *
     * @return ResponseBuilderItem|ResponseBuilder
     */
    public function end() : ResponseBuilderItem|ResponseBuilder
    {
        return $this->parent;
    }
}
