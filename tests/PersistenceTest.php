<?php

namespace Tests;

use MilesChou\Toggle\Providers\ResultProvider;
use MilesChou\Toggle\Toggle;

class PersistenceTest extends \PHPUnit_Framework_TestCase
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
    public function shouldReturnStaticResultWhenCreateFeatureUsingStatic()
    {
        $this->target->create('foo');
        $this->target->feature('foo')->result(false);

        $data = ResultProvider::create()
            ->feature('foo', true);

        $this->target->result($data);

        $this->assertFalse($this->target->isActive('foo'));
    }
}
