<?php

namespace Takemo101\SimpleVM;

use ArrayAccess;
use IteratorAggregate;
use Countable;
use JsonSerializable;
use ArrayIterator;
use Traversable;

/**
 * array access object
 *
 * @implements IteratorAggregate<mixed>
 * @implements ArrayAccess<string, mixed>
 */
class ArrayAccessObject implements ArrayAccess, IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var mixed[]
     */
    protected array $data;

    /**
     * constructor
     *
     * @param mixed[] $data
     */
    final public function __construct(
        array $data = [],
    ) {
        $this->data = [];
        foreach ($data as $key => $value) {
            $this->data[$key] = is_array($value) ? new static($value) : $value;
        }
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
     * @param mixed $key
     * @return boolean
     */
    public function offsetExists(mixed $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * implements ArrayAccess
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->offsetExists($key) ? $this->data[$key] : null;
    }

    /**
     * implements ArrayAccess
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * implements ArrayAccess
     *
     * @param mixed $key
     * @return void
     */
    public function offsetUnset(mixed $key): void
    {
        unset($this->data[$key]);
    }

    /**
     * implements IteratorAggregate
     *
     * @return ArrayIterator<string, mixed>
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
