<?php

namespace Tests;

use MilesChou\Toggle\Context;
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
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->assertNull($this->target->isActive());
    }

    public function invalidProcessor()
    {
        return [
            [123],
            [3.14],
            [''],
            ['str'],
            [[]],
            [new \stdClass()],
        ];
    }

    /**
     * @test
     * @dataProvider invalidProcessor
     */
    public function shouldThrowExceptionWhenNewWithInvalidParam($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid processor or result');

        new Feature($invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenNewWithDefaultValue()
    {
        $target = new Feature(true);

        $this->assertTrue($target->isActive());
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenNewWithDefaultValue()
    {
        $target = new Feature(false);

        $this->assertFalse($target->isActive());
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
    public function shouldReturnDifferentResultWhenGivenDifferentContext()
    {
        $target = Feature::create(function (Context $context) {
            $id = $context->getParam('id');

            return 0 === $id % 2;
        });

        $this->assertFalse($target->isActive(Context::create(['id' => 1])));
        $this->assertTrue($target->isActive(Context::create(['id' => 2])));
    }
}
