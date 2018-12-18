<?php

namespace Tests;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use RuntimeException;

class FeatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->setExpectedException(RuntimeException::class);

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
        $target = Feature::create(function ($context) {
            $id = $context['id'];

            return 0 === $id % 2;
        });

        $this->assertFalse($target->isActive(['id' => 1]));
        $this->assertTrue($target->isActive(['id' => 2]));
    }
}
