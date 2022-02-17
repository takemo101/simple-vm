<?php

namespace Takemo101\SimpleVM;

use JsonSerializable;

/**
 * view model class
 */
abstract class ViewModel implements JsonSerializable
{
    /**
     * ignores property or method names
     *
     * @var string[]
     */
    protected static array $ignores = [
        'toArray',
        'toArrayAccessObject',
        'jsonSerialize',
        'hasIgnoresName',
        'of',
    ];

    /**
     * ignores property or method names
     *
     * @var string[]
     */
    protected array $__ignores = [
        //
    ];

    /**
     * has name in ignores
     *
     * @param string $name
     * @return boolean
     */
    public function hasIgnoresName(string $name): bool
    {
        if (strpos($name, '__') === 0) {
            return true;
        }

        $ignores = static::$ignores;
        $ignores[] = __FUNCTION__;

        return in_array($name, [
            ...$ignores,
            ...$this->__ignores,
        ]);
    }

    /**
     * to json
     *
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * to array
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        $adapter = ViewModelDataAdapterCreator::create($this);
        return $adapter->toAllData();
    }

    /**
     * to array access object
     *
     * @return ArrayAccessObject
     */
    public function toArrayAccessObject(): ArrayAccessObject
    {
        return new ArrayAccessObject($this->toArray());
    }

    /**
     * factory
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function of(mixed ...$parameters): static
    {
        return new static(...$parameters);
    }
}
