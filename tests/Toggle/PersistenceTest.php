<?php

namespace Tests\Toggle;

use MilesChou\Toggle\Toggle;
use PHPUnit\Framework\TestCase;

class PersistenceTest extends TestCase
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

        $this->target->result([
            'foo' => true,
        ]);

        $this->assertFalse($this->target->isActive('foo'));
    }
}
