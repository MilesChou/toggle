<?php

namespace Tests\Concerns;

class FeatureTraitTest extends \PHPUnit_Framework_TestCase
{
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
    public function shouldThrowExceptionWithInvalidFeature($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid');

        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\FeatureAwareTrait');
        $target->createFeature('foo', $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetFeatureAndFeatureNotFound()
    {
        $this->setExpectedException('InvalidArgumentException', 'Feature \'not-exist\' is not found');

        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\FeatureAwareTrait');
        $target->getFeature('not-exist');
    }
}
