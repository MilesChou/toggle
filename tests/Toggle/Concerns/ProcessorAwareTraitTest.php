<?php

namespace Tests\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use PHPUnit\Framework\TestCase;

class ProcessorAwareTraitTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Processor must be callable
     */
    public function shouldThrowExceptionWhenSetNotCallbackProcessor()
    {

        $target = Feature::create();
        $target->processor('not a callback');
    }
}
