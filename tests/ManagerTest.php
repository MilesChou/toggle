<?php

namespace Tests;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use MilesChou\Toggle\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Manager();
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
        $this->assertInstanceOf('MilesChou\\Toggle\\Manager', $this->target);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCallIsActiveWithNoData()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->target->isActive('myFeature');
    }
}
