<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleVM\{
    SimpleCallableResolver,
    ViewModel,
    CallMethodException,
};

/**
 * callable resolver test
 */
class CallableResolverTest extends TestCase
{
    /**
     * @test
     */
    public function createCallableResolver__OK()
    {
        $resolver = new SimpleCallableResolver;
        $model = new CallableResolverTestViewModel;

        $this->assertEquals(
            $resolver->call($model, 'a'),
            'A'
        );
        $this->assertEquals(
            $resolver->call($model, 'b'),
            'B'
        );
    }

    /**
     * @test
     */
    public function createCallableResolver__NG()
    {
        $this->expectException(CallMethodException::class);

        $resolver = new SimpleCallableResolver;
        $model = new CallableResolverTestViewModel;

        $resolver->call($model, 'c');
    }

    /**
     * @test
     */
    public function copyCallableResolver__OK()
    {
        $resolver = new SimpleCallableResolver;
        $copy = $resolver->copy();

        $this->assertTrue($copy !== $resolver);
    }
}

/**
 * test view model class
 */
class CallableResolverTestViewModel extends ViewModel
{
    /**
     * @return string
     */
    public function a(): string
    {
        return 'A';
    }

    /**
     * @return string
     */
    public function b(): string
    {
        return 'B';
    }
}
