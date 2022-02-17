<?php

namespace Takemo101\SimpleVM;

use TypeError;

final class SimpleCallableResolver implements CallableResolverInterface
{
    /**
     * resolve call model method
     *
     * @param ViewModel $model
     * @return mixed
     * @throws CallMethodException
     */
    public function call(ViewModel $model, string $method): mixed
    {
        if (!method_exists($model, $method)) {
            throw new CallMethodException("not found method error: [{$method}]");
        }

        $callable = [$model, $method];

        if (!is_callable($callable)) {
            throw new CallMethodException("not callable error: [{$method}]");
        }

        return call_user_func($callable);
    }

    /**
     * deep copy method
     *
     * @return static
     */
    public function copy(): static
    {
        return new static;
    }
}
