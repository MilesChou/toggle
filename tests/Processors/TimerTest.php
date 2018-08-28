<?php

namespace Tests\Processors;

use Carbon\Carbon;
use MilesChou\Toggle\Processors\Processor;
use MilesChou\Toggle\Processors\Timer;

class TimerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnCorrectWhenTimeIsUp()
    {
        $target = Timer::create([
            'timer' => [
                11111 => 'f1',
                33333 => 'f3',
                22222 => 'f2',
            ],
        ]);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertNull($target());

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f2', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(40000));
        $this->assertSame('f3', $target());
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenUseDatetimeFormat()
    {
        $target = Timer::create([
            'timer' => [
                '2018-01-01' => 'f1',
                '2018-03-01' => 'f3',
                '2018-02-01' => 'f2',
            ],
        ]);

        Carbon::setTestNow(Carbon::parse('2017-12-31'));
        $this->assertNull($target());

        Carbon::setTestNow(Carbon::parse('2018-01-20'));
        $this->assertSame('f1', $target());

        Carbon::setTestNow(Carbon::parse('2018-02-10'));
        $this->assertSame('f2', $target());

        Carbon::setTestNow(Carbon::parse('2018-08-01'));
        $this->assertSame('f3', $target());
    }

    /**
     * @test
     */
    public function shouldReturnDefaultWhenTimeIsNotUp()
    {
        $target = Timer::create([
            'default' => 'def',
            'timer' => [
                11111 => 'f1',
            ],
        ]);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertSame('def', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $target());
    }

    /**
     * @test
     */
    public function shouldReturnCorrectWhenTimeIsUpWithThenFalse()
    {
        $target = Timer::create([
            'then' => false,
            'timer' => [
                11111 => 'f1',
                33333 => 'f3',
                22222 => 'f2',
            ],
        ]);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertSame('f1', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f2', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f3', $target());

        Carbon::setTestNow(Carbon::createFromTimestamp(40000));
        $this->assertNull($target());
    }

    /**
     * @test
     */
    public function shouldReturnJsonWhenToArray()
    {
        $expected = [
            'class' => 'MilesChou\\Toggle\\Processors\\Timer',
            'config' => [
                'default' => null,
                'timer' => [
                    22222 => 'f2',
                    11111 => 'f1',
                ],
                'then' => true,
            ],
        ];

        $target = Timer::create([
            'timer' => [
                11111 => 'f1',
                22222 => 'f2',
            ],
        ]);

        $this->assertSame($expected, $target->toArray());
    }

    /**
     * @test
     */
    public function shouldRestoreFromJsonWhenRetrieve()
    {
        $actual = Processor::retrieve([
            'class' => 'MilesChou\\Toggle\\Processors\\Timer',
            'config' => [
                'default' => null,
                'timer' => [
                    22222 => 'f2',
                    11111 => 'f1',
                ],
                'then' => true,
            ],
        ]);

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertNull($actual());

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $actual());

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f2', $actual());
    }
}
