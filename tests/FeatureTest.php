<?php

namespace Tests;

use MilesChou\Toggle\Feature;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Feature
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Feature(function () {
            return null;
        });
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenOn()
    {
        $this->assertTrue($this->target->on()->isActive());
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenOff()
    {
        $this->assertFalse($this->target->off()->isActive());
    }

    /**
     * @test
     */
    public function shouldReturnNullWhenDefaultWithCallableReturnNull()
    {
        $this->assertNull($this->target->isActive());
    }
}
