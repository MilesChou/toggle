<?php

namespace Tests\Concerns;

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
    public function shouldThrowExceptionWithInvalidGroup($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid processor or result');

        $target = $this->getMockForTrait('MilesChou\Toggle\Concerns\GroupTrait');
        $target->createGroup('foo', [], $invalidProcessor);
    }
}
