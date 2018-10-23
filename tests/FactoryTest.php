<?php

namespace Tests;

use Carbon\Carbon;
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

        $this->assertSame('f1', $actual->select('g1'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigProcess()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/timer_process.yaml');
        $actual->setPreserve(false);

        $this->assertInstanceOf(Toggle::class, $actual);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertNull($actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f2', $actual->select('g1'));

        Carbon::setTestNow(Carbon::createFromTimestamp(40000));
        $this->assertSame('f3', $actual->select('g1'));
    }

    /**
     * @test
     */
    public function shouldReturnCorrectResultWhenCreateFromConfigProcessWithBucketProcess()
    {
        $actual = (new Factory())->createFromFile(__DIR__ . '/Fixtures/bucket_process.yaml');

        $this->assertInstanceOf(Toggle::class, $actual);

        $this->assertSame('f1', $actual->select('g1'));
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
