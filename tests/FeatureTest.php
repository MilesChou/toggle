<?php

namespace Tests;

use InvalidArgumentException;
use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $target = Feature::create(function () {
            return null;
        });

        $target->isActive();
    }

    /**
     * @test
     */
    public function shouldReturnFalseDefault()
    {
        $target = Feature::create();

        $this->assertFalse($target->isActive());
    }

    /**
     * @test
     */
    public function shouldReturnDifferentResultWhenGivenDifferentContext()
    {
        $target = Feature::create(function (Context $context) {
            $id = $context->getParam('id');

            return 0 === $id % 2;
        });

        $this->assertFalse($target->isActive(Context::create(['id' => 1])));
        $this->assertTrue($target->isActive(Context::create(['id' => 2])));
    }
}
