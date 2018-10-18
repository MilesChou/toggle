<?php

namespace Tests\Processors;

use MilesChou\Toggle\Processors\Bucket;

class BucketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnFalseWithBucketIs0()
    {
        $target = Bucket::create(0);

        $this->assertFalse($target());
    }

    /**
     * @test
     */
    public function shouldReturnTrueWithBucketIs100()
    {
        $target = Bucket::create(100);

        $this->assertTrue($target());
    }
}
