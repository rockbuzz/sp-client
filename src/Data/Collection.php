<?php

namespace Rockbuzz\SpClient\Data;

use ReflectionClass;
use Rockbuzz\SpClient\ClientException;
use Illuminate\Support\Collection as SupportCollection;

abstract class Collection
{
    /**
     * @var SupportCollection
     */
    public $data;

    /**
     * @var Links
     */
    public $links;

    /**
     * @var Meta
     */
    public $meta;

    public function __construct(array $parameters)
    {
        $this->data = SupportCollection::make($this->mapItems($this->getOrThrows('data', $parameters)));
        $this->links = Links::fromArray($this->getOrThrows('links', $parameters));
        $this->meta = Meta::fromArray($this->getOrThrows('meta', $parameters));
    }

    /**
     * Item must be a subclass of \Rockbuzz\SpClient\Data\DataTransferObject
     *
     * @return string
     */
    abstract protected function itemType(): string;

    /**
     * @param array $parameters
     * @return self instance subclass
     */
    public static function make(array $parameters)
    {
        return new static($parameters);
    }

    /**
     * @param array $data
     * @return array
     * @throws ClientException
     */
    protected function mapItems(array $data): array
    {
        if ((new ReflectionClass($this->itemType()))->isSubclassOf(DataTransferObject::class)) {
            return $this->itemType()::arrayOf($data);
        }

        throw new ClientException(
            "Item {$this->itemType()} must be a subclass of \Rockbuzz\SpClient\Data\DataTransferObject"
        );
    }

    /**
     * @param array $parameters
     * @return array
     * @throws ClientException
     */
    protected function getOrThrows(string $key, array $parameters): array
    {
        if (isset($parameters[$key])) {
            return $parameters[$key];
        }

        throw new ClientException("Key {$key} does not exists");
    }
}
