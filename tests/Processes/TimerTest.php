<?php

namespace Tests\Processes;

use Carbon\Carbon;
use MilesChou\Toggle\Processes\Process;
use MilesChou\Toggle\Processes\Timer;

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
    public function shouldReturnJsonWhenSerialize()
    {
        $target = Timer::create([
            'timer' => [
                11111 => 'f1',
                22222 => 'f2',
            ],
        ]);

        $this->assertSame('{"class":"MilesChou\\\\Toggle\\\\Processes\\\\Timer","config":{"default":null,"timer":{"22222":"f2","11111":"f1"},"then":true}}', $target->serialize());
    }

    /**
     * @test
     */
    public function shouldRestoreFromJsonWhenUnserialize()
    {
        $actual = Process::unserialize('{"class":"MilesChou\\\\Toggle\\\\Processes\\\\Timer","config":{"default":null,"timer":{"22222":"f2","11111":"f1"},"then":true}}');

        Carbon::setTestNow(Carbon::createFromTimestamp(10000));
        $this->assertNull($actual());

        Carbon::setTestNow(Carbon::createFromTimestamp(20000));
        $this->assertSame('f1', $actual());

        Carbon::setTestNow(Carbon::createFromTimestamp(30000));
        $this->assertSame('f2', $actual());
    }
}
