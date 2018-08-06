<?php

namespace Tests;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

class GroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Group
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Group([
            'feature1' => new Feature(),
            'feature2' => new Feature(),
        ], function () {
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
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->setExpectedException('RuntimeException');

        $this->assertNull($this->target->select());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenResultOutOfRange()
    {
        $this->setExpectedException('RuntimeException');

        $this->target->setProcessedResult('feature3');
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenOn()
    {
        $this->target->setProcessedResult('feature1');

        $this->assertSame('feature1', $this->target->select());
    }
}
