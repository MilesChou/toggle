<?php

namespace Tests\Concerns;

use MilesChou\Toggle\Feature;

class ProcessorAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenNotDefineReturnAndNotDefineProcessor()
    {
        $this->setExpectedException('RuntimeException', 'It\'s must provide a processor to decide feature');

        $target = new Feature();
        $target->isActive();
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenSetNotCallbackProcessor()
    {
        $this->setExpectedException('InvalidArgumentException', 'Processor must be callable');

        $target = new Feature();
        $target->setProcessor('not a callback');
    }
}
