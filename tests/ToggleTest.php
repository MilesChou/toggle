<?php

namespace Tests;

use MilesChou\Toggle\Toggle;

class ToggleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Toggle
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Toggle();
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function smoke()
    {
        $this->assertInstanceOf(Toggle::class, $this->target);
    }
}
