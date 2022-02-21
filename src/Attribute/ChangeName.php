<?php

namespace Takemo101\SimpleVM\Attribute;

use Attribute;
use InvalidArgumentException;

/**
 * change view model property or method name attribute class
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class ChangeName
{
    /**
     * @var string
     */
    const NameRegex = '/^[a-zA-Z0-9_]+$/';

    /**
     * constructor
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct(
        private string $name,
    ) {
        if (strlen($name) == 0 || !preg_match(self::NameRegex, $name)) {
            throw new InvalidArgumentException("constructor argument error: [{$name}] is a string that cannot be used in the name");
        }
    }

    /**
     * get change name
     *
     * @return string
     */
    public function getChangeName(): string
    {
        return $this->name;
    }
}
