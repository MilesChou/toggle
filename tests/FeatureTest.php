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
        $this->target = new Feature('whatever', function () {
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
            [true],
            [false],
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
    public function shouldThrowExceptionWhenNewWithInvalidProcessor($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid');

        new Feature('whatever', $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldReturnDifferentResultWhenGivenDifferentContext()
    {
        $target = Feature::create('whatever', function (Context $context) {
            $id = $context->getParam('id');

            return 0 === $id % 2;
        });

        $this->assertFalse($target->isActive(Context::create(['id' => 1])));
        $this->assertTrue($target->isActive(Context::create(['id' => 2])));
    }
}
