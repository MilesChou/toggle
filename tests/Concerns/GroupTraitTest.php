<?php

namespace Tests\Concerns;

use MilesChou\Toggle\Concerns\GroupAwareTrait;
use MilesChou\Toggle\Toggle;

class GroupTraitTest extends \PHPUnit_Framework_TestCase
{
    public function invalidProcessor()
    {
        return [
            [true],
            [false],
            [123],
            [3.14],
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

        $target = new Toggle();

        $target->createGroup('whatever', [], $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetGroupAndGroupNotFound()
    {
        $this->setExpectedException('InvalidArgumentException', 'Group \'not-exist\' is not found');

        $target = $this->getMockForTrait(GroupAwareTrait::class);
        $target->getGroup('not-exist');
    }
}
