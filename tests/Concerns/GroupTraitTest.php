<?php

namespace Tests\Concerns;

class GroupTraitTest extends \PHPUnit_Framework_TestCase
{
    public function invalidGroup()
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
     * @dataProvider invalidGroup
     */
    public function shouldThrowExceptionWithInvalidGroup($invalidGroup)
    {
        $this->setExpectedException('InvalidArgumentException', 'The $group must be Feature or callable or string');

        $target = $this->getMockForTrait('MilesChou\Toggle\Concerns\GroupTrait');
        $target->addGroup('foo', [], $invalidGroup);
    }
}
