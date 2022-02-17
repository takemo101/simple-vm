<?php

namespace Takemo101\SimpleVM;

interface CopyInterface
{
    /**
     * deep copy method
     *
     * @return static
     */
    public function copy(): static;
}
