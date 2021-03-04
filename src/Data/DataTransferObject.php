<?php

namespace Rockbuzz\SpClient\Data;

abstract class DataTransferObject
{
    /**
     * @var array
     */
    private $data = [];

    public function __construct(array $parameters = [])
    {
        foreach ($this->properties() as $property) {
            $this->{$property} = $parameters[$property];
        }
    }

    /**
     * Defines item properties
     *
     * @return array
     */
    abstract protected function properties(): array;

    public static function fromArray(array $parameters)
    {
        return new static($parameters);
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
            function ($data) {
                return static::fromArray($data);
            },
            $parameters
        );
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
