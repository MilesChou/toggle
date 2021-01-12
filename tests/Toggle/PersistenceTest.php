<?php

namespace Tests\Toggle;

use MilesChou\Toggle\Persistence\Memory;
use MilesChou\Toggle\Toggle;
use PHPUnit\Framework\TestCase;

class PersistenceTest extends TestCase
{
    /**
     * @var Toggle
     */
    private $target;

    protected function setUp(): void
    {
        $this->target = new Toggle();
    }

    protected function tearDown(): void
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

    /**
     * @test
     */
    public function shouldSavePersistenceWhenCallSave()
    {
        $this->target->create('foo');
        $this->target->feature('foo')->result(true);
        $this->target->create('bar');
        $this->target->feature('bar')->result(false);

        $persistence = new Memory();

        $this->target->save($persistence);

        $this->assertSame([
            'foo' => true,
            'bar' => false,
        ], $persistence->restore());
    }

    /**
     * @test
     */
    public function shouldLoadPersistenceWhenCallLoad()
    {
        $this->target->create('foo');
        $this->target->create('bar');

        $persistence = new Memory();
        $persistence->store([
            'foo' => true,
            'bar' => false,
        ]);

        $this->target->load($persistence);

        $this->assertTrue($this->target->isActive('foo'));
        $this->assertFalse($this->target->isActive('bar'));
        $this->assertFalse($this->target->isActive('unknown'));
    }
}
