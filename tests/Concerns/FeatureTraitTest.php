<?php

namespace Tests\Concerns;

class FeatureTraitTest extends \PHPUnit_Framework_TestCase
{
    public function invalidFeature()
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
     * @dataProvider invalidFeature
     */
    public function shouldThrowExceptionWithInvalidFeature($invalidFeature)
    {
        $this->setExpectedException('InvalidArgumentException', 'The param $feature must be Feature instance');

        $target = $this->getMockForTrait('MilesChou\Toggle\Concerns\FeatureTrait');
        $target->addFeature('foo', $invalidFeature);
    }
}
