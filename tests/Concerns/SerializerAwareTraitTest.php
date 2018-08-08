<?php

namespace Tests\Concerns;

class SerializerAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenDriverNotFound()
    {
        $this->setExpectedException('InvalidArgumentException', 'Class \'unknown\' not found');

        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\SerializerAwareTrait');
        $target->setSerializerDriver('unknown');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenDriverIsNotInstanceOfSerializerInterface()
    {
        $this->setExpectedException('RuntimeException', 'Class \'stdClass\' must be implement SerializerInterface');

        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\SerializerAwareTrait');
        $target->setSerializerDriver('stdClass');

        $target->serialize();
    }
}
