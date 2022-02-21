<?php

namespace Takemo101\SimpleVM\Attribute;

use Attribute;

/**
 * ignore view model property or method attribute class
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY)]
class Ignore
{
    //
}
