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
        $this->setExpectedException('InvalidArgumentException', 'Processor is not valid processor or result');

        $target = $this->getMockForTrait('MilesChou\Toggle\Concerns\FeatureTrait');
        $target->createFeature('foo', $invalidProcessor);
    }
}
