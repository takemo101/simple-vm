<?php

namespace Takemo101\SimpleVM;

use ArrayAccess;
use IteratorAggregate;
use Countable;
use JsonSerializable;
use ArrayIterator;
use Traversable;

class ArrayAccessObject implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable
{
    /**
     * constructor
     *
     * @param mixed[] $data
     */
    public function __construct(
        protected array $data = [],
    ) {
        //
    }

    /**
     * implements Countable
     *
     * @return integer
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * magic method
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * magic method
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        $data = $this->offsetGet($name);
        return is_array($data) ? new static($data) : $data;
    }

    /**
     * magic method
     *
     * @param string $name
     * @return boolean
     */
    public function __isset(string $name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * magic method
     *
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        $this->offsetUnset($name);
    }

    /**
     * implements ArrayAccess
     *
     * @param string $key
     * @return boolean
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * implements ArrayAccess
     *
     * @param string $key
     * @return void
     */
    public function offsetGet($key)
    {
        return $this->offsetExists($key) ? $this->data[$key] : null;
    }

    /**
     * implements ArrayAccess
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * implements ArrayAccess
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->data[$key]);
    }

    /**
     * implements IteratorAggregate
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * to array
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * to json
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
