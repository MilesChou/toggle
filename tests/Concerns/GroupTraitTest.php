<?php

namespace Tests\Concerns;

use MilesChou\Toggle\Manager;

class GroupTraitTest extends \PHPUnit_Framework_TestCase
{
    public function invalidProcessor()
    {
        return [
            [true],
            [false],
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
    public function shouldThrowExceptionWhenCreateGroupWithInvalidGroup($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid processor or result');

        $target = new Manager();

        $target->createGroup('foo', [], $invalidProcessor);
    }
}
