<?php

namespace Takemo101\SimpleVM;

/**
 * view model data adapter factory class
 * singleton class
 */
final class ViewModelDataAdapterCreator
{
    /**
     * @var CallableResolverInterface|null
     */
    private static ?CallableResolverInterface $defaultCallableResolver = null;

    /**
     * private constructor
     */
    private function __construct()
    {
        //
    }

    /**
     * set default callable resolver
     *
     * @param CallableResolverInterface $defaultCallableResolver
     * @return void
     */
    public static function setDefaultCallableResolver(
        CallableResolverInterface $defaultCallableResolver
    ): void {
        self::$defaultCallableResolver = $defaultCallableResolver;
    }

    /**
     * get default callable resolver
     *
     * @return CallableResolverInterface
     */
    public static function getDefaultCallableResolver(): CallableResolverInterface
    {
        if (!self::$defaultCallableResolver) {
            self::$defaultCallableResolver = new SimpleCallableResolver;
        }

        return self::$defaultCallableResolver;
    }

    /**
     * factory
     *
     * @param ViewModel $model
     * @return ViewModelDataAdapter
     */
    public static function create(ViewModel $model): ViewModelDataAdapter
    {
        return new ViewModelDataAdapter(
            $model,
            self::getDefaultCallableResolver()->copy(),
        );
    }
}
