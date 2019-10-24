<?php

namespace Tests\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FeatureTraitTest extends TestCase
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Processor must be callable
     */
    public function shouldThrowExceptionWithInvalidFeature($invalidProcessor)
    {
        $target = $this->getMockForTrait(FeatureAwareTrait::class);
        $target->create('foo', $invalidProcessor);
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Feature 'not-exist' is not found
     */
    public function shouldThrowExceptionWhenGetFeatureAndFeatureNotFound()
    {
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
