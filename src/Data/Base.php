<?php

namespace Rockbuzz\SpClient\Data;

use ErrorException;

abstract class Base
{
    /** @var array */
    protected $item;

    public function __construct(array $item = [])
    {
        $this->item = $item;
    }

    /**
     * Transforms an array collection to the current item
     *
     * @param array $parameters
     * @return array
     */
    public static function arrayOf(array $parameters): array
    {
        return array_map(
            function ($item) {
                return new static($item);
            },
            $parameters
        );
    }

    /**
     * @param $property
     * @return mixed
     * @throws ErrorException
     */
    public function __get($property)
    {
        if (array_key_exists($property, $this->item)) {
            return $this->item[$property];
        }

        $class = get_class($this);

        throw new ErrorException("Undefined property: {$class}::{$property}");
    }

    /**
     * @param $property
     * @return bool
     */
    public function __isset($property): bool
    {
        return isset($this->item[$property]);
    }
}
