<?php

namespace Takemo101\SimpleVM;

final class SimpleCallableResolver implements CallableResolverInterface
{
    /**
     * resolve call model method
     *
     * @param ViewModel $model
     * @return mixed
     */
    public function call(ViewModel $model, string $method): mixed
    {
        return call_user_func([$model, $method]);
    }

    /**
     * deep copy method
     *
     * @return static
     */
    public function copy(): static
    {
        return new static();
    }
}
