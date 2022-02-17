<?php

namespace Takemo101\SimpleVM;

interface CallableResolverInterface extends CopyInterface
{
    /**
     * resolve call model method
     *
     * @param ViewModel $model
     * @return mixed
     */
    public function call(ViewModel $model, string $method): mixed;
}
