<?php

namespace Takemo101\SimpleVM;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use OutOfBoundsException;

/**
 * view model data adapter class
 */
final class ViewModelDataAdapter
{
    /**
     * view model initialize data method name
     *
     * @var string
     */
    const InitializeMethodName = '__data';

    /**
     * constructor
     *
     * @param ViewModel $model
     * @param CallableResolverInterface $callableResolver
     */
    public function __construct(
        private ViewModel $model,
        private CallableResolverInterface $callableResolver,
    ) {
        //
    }

    /**
     * to all data
     *
     * @return mixed[]
     */
    public function toAllData(): array
    {
        // view model property data
        $properties = self::toPropertyData();

        // view model method data
        $methods = self::toMethodData();

        $result = [
            ...$properties,
            ...$methods,
        ];

        if ($data = $this->toInitializeMethodData()) {
            return [
                ...$data,
                ...$result,
            ];
        }

        return $result;
    }

    /**
     * to initialize method data
     *
     * @return mixed[]|null
     * @throws OutOfBoundsException;
     */
    public function toInitializeMethodData(): ?array
    {
        if (method_exists($this->model, self::InitializeMethodName)) {
            $data = $this->callableResolver->call($this->model, self::InitializeMethodName);
            if (!is_array($data)) {
                throw new OutOfBoundsException('initialize data is not array');
            }
            return $data;
        }
        return null;
    }

    /**
     * to property data
     *
     * @return mixed[]
     */
    public function toPropertyData(): array
    {
        $class = new ReflectionClass($this->model);

        /**
         * @var ReflectionProperty[]
         */
        $reflections = array_filter($class->getProperties(
            ReflectionProperty::IS_PUBLIC,
        ), function (ReflectionProperty $property) {
            return !$this->model->hasIgnoresName($property->getName());
        });

        return $this->arrayMapWithKey($reflections, function (ReflectionProperty $property) {
            return [
                $property->getName() => $this->model->{$property->getName()},
            ];
        });
    }

    /**
     * to method data
     *
     * @return mixed[]
     */
    public function toMethodData(): array
    {
        $class = new ReflectionClass($this->model);

        /**
         * @var ReflectionMethod[]
         */
        $reflections = array_filter($class->getMethods(
            ReflectionMethod::IS_PUBLIC,
        ), function (ReflectionMethod $method) {
            $name = $method->getName();
            return $name == self::InitializeMethodName ? false : !$this->model->hasIgnoresName($name);
        });

        return $this->arrayMapWithKey($reflections, function (ReflectionMethod $method) {
            return [
                $method->getName() => $this->call($method),
            ];
        });
    }

    /**
     * call model method
     *
     * @param ReflectionMethod $method
     * @return mixed
     */
    private function call(ReflectionMethod $method): mixed
    {
        $name = $method->getName();

        if ($method->getNumberOfParameters() === 0) {
            return $this->model->{$name}();
        }

        return $this->callableResolver->call($this->model, $name);
    }

    /**
     * array map with key
     *
     * @param mixed[] $data
     * @param callable $callback
     * @return mixed[]
     */
    private function arrayMapWithKey(array $data, callable $callback): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $assoc = (array)call_user_func_array($callback, [$value, $key]);

            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return $result;
    }
}
