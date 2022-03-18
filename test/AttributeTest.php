<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleVM\ViewModel;
use Takemo101\SimpleVM\Attribute\{
    ChangeName,
    Ignore,
};
use InvalidArgumentException;

/**
 * attribute test
 */
class AttributeTest extends TestCase
{
    /**
     * @test
     */
    public function createViewModelWithAttribute__OK()
    {
        $model = new TestAttributeViewModel(
            'A',
            2,
            [
                'cc' => 'C',
            ],
            4.4,
            'EE',
        );

        $data = $model->toArray();

        $this->assertFalse(array_key_exists('a', $data));
        $this->assertTrue(array_key_exists('bb', $data));
        $this->assertEquals(
            $data['bb'],
            2,
        );
        $this->assertTrue(array_key_exists('cc', $data));
        $this->assertEquals(
            $data['cc']['cc'],
            'C',
        );
        $this->assertFalse(array_key_exists('d', $data));
        $this->assertEquals(
            $data['e'],
            'EE',
        );
        $this->assertEquals(
            $data['f'],
            'F',
        );
        $this->assertFalse(array_key_exists('g', $data));
        $this->assertFalse(array_key_exists('h', $data));
    }

    /**
     * @test
     */
    public function createViewModelWithAttribute__NG()
    {
        $this->expectException(InvalidArgumentException::class);
        $model = new TestChangeNameViewModel(
            'A',
        );

        $model->toArray();
    }
}

/**
 * test attribute view model class
 */
class TestAttributeViewModel extends ViewModel
{
    protected array $__ignores = [
        'g'
    ];

    /**
     * constructor
     *
     * @param string $a
     * @param integer $b
     * @param string[] $c
     * @param float $d
     * @param string $e
     */
    public function __construct(
        #[Ignore]
        public string $a,
        #[ChangeName('bb')]
        public int $b,
        private array $c,
        #[Ignore]
        public float $d,
        public string $e,
    ) {
        //
    }

    /**
     * data method c
     *
     * @return string[]
     */
    #[ChangeName('cc')]
    public function c(): array
    {
        return $this->c;
    }

    /**
     * data method g
     *
     * @return string
     */
    public function g(): string
    {
        return 'G';
    }

    /**
     * data method h
     *
     * @return string
     */
    #[Ignore]
    public function h(): string
    {
        return 'H';
    }

    /**
     * initialize data method
     *
     * @return mixed[]
     */
    public function __data(): array
    {
        return [
            'e' => 'E',
            'f' => 'F',
        ];
    }
}

/**
 * test change name view model class
 */
class TestChangeNameViewModel extends ViewModel
{
    /**
     * constructor
     *
     * @param string $a
     */
    public function __construct(
        #[ChangeName('a_a')]
        public string $a,
    ) {
        //
    }

    /**
     * data method c
     *
     * @return string[]
     */
    #[ChangeName('b-b')]
    public function b(): array
    {
        return $this->c;
    }
}
