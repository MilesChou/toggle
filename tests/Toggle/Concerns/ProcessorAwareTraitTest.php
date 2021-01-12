<?php

namespace Tests\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use PHPUnit\Framework\TestCase;

class ProcessorAwareTraitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenSetNotCallbackProcessor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Processor must be callable');

        $target = Feature::create();
        $target->processor('not a callback');
    }
}
