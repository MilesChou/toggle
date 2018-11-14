<?php

namespace Tests\Concerns;

use MilesChou\Toggle\Feature;

class ProcessorAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenSetNotCallbackProcessor()
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor must be callable');

        $target = Feature::create();
        $target->setProcessor('not a callback');
    }
}
