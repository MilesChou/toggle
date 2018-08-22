<?php

namespace Tests;

use Carbon\Carbon;
use MilesChou\Toggle\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigBasic()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/basic.yaml');

        $this->assertInstanceOf('MilesChou\\Toggle\\Manager', $actual);

        $this->assertSame('f1', $actual->select('g1'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigProcess()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/timer_process.yaml');
        $actual->setPreserve(false);

        $this->assertInstanceOf('MilesChou\\Toggle\\Manager', $actual);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertNull($actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f2', $actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(40000));
        $this->assertSame('f3', $actual->select('g1'));
    }
}
