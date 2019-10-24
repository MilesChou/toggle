<?php

namespace Tests\Toggle;

use InvalidArgumentException;
use MilesChou\Toggle\Factory;
use MilesChou\Toggle\Toggle;
use PHPUnit\Framework\TestCase;
use Tests\Toggle\Fixtures\DummyProcessor;

class FactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnEmptyToggleWhenCreateFromEmptyArray()
    {
        $actual = (new Factory())->createFromArray([]);

        $this->assertInstanceOf(Toggle::class, $actual);
        $this->assertEmpty($actual->all());
    }

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
    public function shouldGenerateProcessorFromArrayConfig()
    {
        /** @var DummyProcessor $actual */
        $actual = (new Factory())->createFromArray([
            'f1' => [
                'processor' => [
                    'class' => DummyProcessor::class,
                    'something' => 'whatever',
                ]
            ],
        ])->processor('f1');

        $this->assertInstanceOf(DummyProcessor::class, $actual);
        $this->assertSame(['something' => 'whatever'], $actual->getConfig());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function shouldThrowExceptionWhenClassIsNotGiven()
    {
        (new Factory())->createFromArray([
            'f1' => [
                'processor' => [
                    'something' => 'whatever',
                ]
            ],
        ]);
    }
}
