<?php

namespace Tests;

use MilesChou\Toggle\Factory;
use MilesChou\Toggle\Providers\ResultProvider;
use MilesChou\Toggle\Toggle;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigBasic()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/basic.yaml');

        $this->assertInstanceOf(Toggle::class, $actual);
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigProcessWithStaticResult()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/static_result.yaml');

        $this->assertInstanceOf(Toggle::class, $actual);
        $this->assertFalse($actual->isActive('f1'));

        $actual->result(
            ResultProvider::create()->feature('f1', true)
        );

        $this->assertFalse($actual->isActive('f1'));
    }
}
