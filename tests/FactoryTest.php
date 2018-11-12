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
        $actual = (new Factory())->createFromArray([
            'f1' => [],
            'f2' => [],
            'f3' => [],
        ]);

        $this->assertInstanceOf(Toggle::class, $actual);
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

        $actual->result(
            ResultProvider::create()->feature('f1', true)
        );

        $this->assertFalse($actual->isActive('f1'));
    }
}
