<?php

namespace Tests;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\DataProviderInterface;
use MilesChou\Toggle\DataProvider;
use MilesChou\Toggle\Factory;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Manager;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfig()
    {
        $actual = Factory::createFromFile(__DIR__ . '/Fixtures/basic.yaml');

        $this->assertInstanceOf('MilesChou\\Toggle\\Manager', $actual);

        $this->assertSame('f1', $actual->select('g1'));
    }
}
