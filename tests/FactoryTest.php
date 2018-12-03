<?php

namespace Tests;

use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Factory;
use MilesChou\Toggle\Processors\Bucket;
use MilesChou\Toggle\Providers\DataProvider;
use MilesChou\Toggle\Toggle;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigBasic()
    {
        $actual = (new Factory())->createFromArray([
            'f1' => [],
            'f2' => [],
            'f3' => [
                'processor' => true
            ],
        ]);

        $this->assertInstanceOf(Toggle::class, $actual);
        $this->assertFalse($actual->isActive('f1'));
        $this->assertFalse($actual->isActive('f2'));
        $this->assertTrue($actual->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigBasicWithProcessor()
    {
        $actual = (new Factory())->createFromArray([
            'f1' => [],
            'f2' => [],
            'f3' => [
                'processor' => [
                    'class' => Bucket::class,
                    'percentage' => 100,
                ]
            ],
        ]);

        $this->assertInstanceOf(Toggle::class, $actual);
        $this->assertFalse($actual->isActive('f1'));
        $this->assertFalse($actual->isActive('f2'));
        $this->assertTrue($actual->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigProcessWithStaticResult()
    {
        $actual = (new Factory())->createFromArray([
            'f1' => [
                'staticResult' => false,
            ],
        ]);

        $this->assertInstanceOf(Toggle::class, $actual);
        $this->assertFalse($actual->isActive('f1'));

        $actual->result([
            'f1' => true,
        ]);

        $this->assertFalse($actual->isActive('f1'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromDataProvider()
    {
        $dataProvider = new DataProvider([
            'f1' => [
                'params' => [],
                'return' => true,
            ],
            'f2' => [
                'params' => [],
                'return' => false,
            ],
            'f3' => [
                'params' => [],
                'return' => false,
            ],
        ]);

        $actual = (new Factory())->createFromDataProvider($dataProvider);

        $this->assertTrue($actual->isActive('f1'));
        $this->assertFalse($actual->isActive('f2'));
        $this->assertFalse($actual->isActive('f3'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenTransferToDataProvider()
    {
        $expected = [
            'f1' => [
                'params' => [],
                'result' => true,
            ],
            'f2' => [
                'params' => [],
                'result' => false,
            ],
            'f3' => [
                'params' => [],
                'result' => false,
            ],
        ];

        $toggle = (new Toggle())
            ->create('f1', true)
            ->create('f2', false)
            ->create('f3', false);


        $actual = (new Factory())->transferToDataProvider($toggle);

        $this->assertInstanceOf(ProviderInterface::class, $actual);
        $this->assertSame($expected, $actual->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenTransferToDataProviderWithContext()
    {
        $expected = [
            'f1' => [
                'params' => [],
                'result' => false,
            ],
        ];

        $toggle = (new Toggle())
            ->setContext(['return' => false])
            ->create('f1', function ($context) {
                return $context['return'];
            });

        $actual = (new Factory())->transferToDataProvider($toggle);

        $this->assertInstanceOf(ProviderInterface::class, $actual);
        $this->assertSame($expected, $actual->toArray());
    }
}
