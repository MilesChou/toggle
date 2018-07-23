<?php

namespace Tests;

use MilesChou\Toggle\Context;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Context();
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
        $this->assertInstanceOf('MilesChou\\Toggle\\Context', $this->target);
    }
}
