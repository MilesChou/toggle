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
     */
    public function shouldThrowExceptionWithInvalidFeature($invalidProcessor)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Processor must be callable');

        $target = $this->getMockForTrait(FeatureAwareTrait::class);
        $target->create('foo', $invalidProcessor);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetFeatureAndFeatureNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Feature 'not-exist' is not found");

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
