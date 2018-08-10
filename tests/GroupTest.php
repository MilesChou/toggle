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
        $this->target = new Group('whatever', [
            'feature1' => new Feature('feature1'),
            'feature2' => new Feature('feature2'),
        ], function () {
            return null;
        });
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    public function invalidProcessor()
    {
        return [
            [true],
            [false],
            [''],
            ['str'],
            [123],
            [3.14],
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
        $this->setExpectedException('InvalidArgumentException', 'Processor must be callable');

        new Group('whatever', [], $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->assertNull($this->target->select());
    }

    /**
     * @test
     */
    public function shouldReturnFeature1InstanceWhenSelectFeature()
    {
        $feature1 = new Feature('feature1');
        $feature2 = new Feature('feature2');

        $target = new Group('whatever', [
            'feature1' => $feature1,
            'feature2' => $feature2,
        ], function () {
            return 'feature1';
        });

        $actual = $target->selectFeature();

        $this->assertSame($feature1, $actual);
    }
}
