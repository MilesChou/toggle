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
        $this->setExpectedException('InvalidArgumentException', 'Processor must be callable');

        $target = new Manager();

        $target->createGroup('foo', [], $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetGroupAndGroupNotFound()
    {
        $this->setExpectedException('InvalidArgumentException', 'Group \'not-exist\' is not found');

        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\GroupAwareTrait');
        $target->getGroup('not-exist');
    }
}
