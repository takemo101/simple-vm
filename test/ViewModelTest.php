<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleVM\ViewModel;

/**
 * view model test
 */
class ViewModelTest extends TestCase
{
    /**
     * @test
     */
    public function createViewModel__OK()
    {
        $model = TestViewModel::of(
            'A',
            2,
            [
                'cc' => 'C',
            ],
            4.4,
            'EE',
        );

        $data = $model->toArray();

        $this->assertEquals(
            $data['a'],
            'A',
        );
        $this->assertEquals(
            $data['b'],
            2,
        );
        $this->assertEquals(
            $data['c']['cc'],
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
    }

    /**
     * @test
     */
    public function createArrayAccessObject__OK()
    {
        $model = TestViewModel::of(
            'A',
            2,
            [
                'cc' => 'C',
            ],
            4.4,
            'EE',
        );

        $data = $model->toArrayAccessObject();

        $this->assertEquals(
            $data->a,
            'A',
        );
        $this->assertEquals(
            $data->b,
            2,
        );
        $this->assertEquals(
            $data->c->cc,
            'C',
        );
        $this->assertFalse(isset($data->d));
        $this->assertTrue(is_null($data->d));
        $this->assertEquals(
            $data->e,
            'EE',
        );
        $this->assertEquals(
            $data->f,
            'F',
        );
        $this->assertFalse(isset($data->g));
        $this->assertTrue(is_null($data->g));

        $array = $data->toArray();

        $this->assertEquals(
            $array['c']->cc,
            'C',
        );
    }
}

/**
 * test view model class
 */
class TestViewModel extends ViewModel
{
    protected array $__ignores = [
        'd',
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
        public string $a,
        public int $b,
        private array $c,
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
