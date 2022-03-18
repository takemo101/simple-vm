<?php

namespace Takemo101\SimpleVM;

use Takemo101\SimpleVM\Attribute\{
    ChangeName,
    Ignore,
};
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

        $result = array_merge(
            $properties,
            $methods,
        );

        if ($data = $this->toInitializeMethodData()) {
            return array_merge(
                $data,
                $result,
            );
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
            return !$this->isIgnoresByReflection($property);
        });

        return $this->arrayMapWithKey($reflections, function (ReflectionProperty $property) {
            return [
                $this->getDataNameByReflection($property) => $this->model->{$property->getName()},
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
            return $method->getName() == self::InitializeMethodName ? false : !$this->isIgnoresByReflection($method);
        });

        return $this->arrayMapWithKey($reflections, function (ReflectionMethod $method) {
            return [
                $this->getDataNameByReflection($method) => $this->call($method),
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

    /**
     * is ignores or method by reflection
     *
     * @param ReflectionProperty|ReflectionMethod $reflection
     * @return boolean
     */
    private function isIgnoresByReflection(ReflectionProperty|ReflectionMethod $reflection): bool
    {
        if ($this->model->hasIgnoresName($reflection->getName())) {
            return true;
        }

        return count($reflection->getAttributes(Ignore::class)) > 0;
    }

    /**
     * get data name by reflection
     *
     * @param ReflectionProperty|ReflectionMethod $reflection
     * @return string
     */
    private function getDataNameByReflection(ReflectionProperty|ReflectionMethod $reflection): string
    {
        $attributes = $reflection->getAttributes(ChangeName::class);
        foreach ($attributes as $attribute) {
            /**
             * @var ChangeName
             */
            $name = $attribute->newInstance();
            return $name->getChangeName();
        }

        return $reflection->getName();
    }
}
