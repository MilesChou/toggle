<?php

namespace Tests\Toggle;

use MilesChou\Toggle\Feature;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FeatureTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowExceptionWhenDefaultWithCallableReturnNull()
    {
        $this->expectException(RuntimeException::class);

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
