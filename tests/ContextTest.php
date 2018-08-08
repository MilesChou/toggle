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
    public function shouldReturnCorrectResultWhenUsingIssetFunction()
    {
        $this->target->set('exist', 'whatever');

        $this->assertTrue($this->target->exist('exist'));
        $this->assertTrue(isset($this->target->exist));
        $this->assertFalse($this->target->exist('notExist'));
        $this->assertFalse(isset($this->target->notExist));
    }
}
