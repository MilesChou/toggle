<?php

namespace Tests\Toggle\Concerns;

use MilesChou\Toggle\Concerns\FeatureAwareTrait;

class FeatureTraitTest extends \PHPUnit_Framework_TestCase
{
    public function invalidProcessor()
    {
        return [
            [123],
            [3.14],
            [''],
            ['str'],
            [new \stdClass()],
        ];
    }

    /**
     * @test
     * @dataProvider invalidProcessor
     */
    public function shouldThrowExceptionWithInvalidFeature($invalidProcessor)
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor must be callable');

        $target = $this->getMockForTrait(FeatureAwareTrait::class);
        $target->create('foo', $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetFeatureAndFeatureNotFound()
    {
        $this->setExpectedException('RuntimeException', "Feature 'not-exist' is not found");

        $target = $this->getMockForTrait(FeatureAwareTrait::class);
        $target->feature('not-exist');
    }

    /**
     * @test
     */
    public function shouldBeOkayWhenCreateWithProcessorInsteadOfParam()
    {
        $target = $this->getMockForTrait('MilesChou\\Toggle\\Concerns\\FeatureAwareTrait');
        $target->create('foo', null, ['some' => 'thing']);

        $this->assertSame('thing', $target->feature('foo')->getParam('some'));
    }
}