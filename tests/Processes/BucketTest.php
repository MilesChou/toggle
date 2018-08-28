<?php

namespace Tests\Processes;

use MilesChou\Toggle\Processes\Bucket;

class BucketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnF1WithOnlyF1()
    {
        $target = Bucket::create([
            'f1' => 10,
        ]);

        $this->assertSame('f1', $target());
    }

    /**
     * @test
     */
    public function shouldReturnF1OrF2()
    {
        $excepted = [
            'f1' => 10,
            'f2' => 1,
            'f3' => 3,
        ];

        $target = Bucket::create($excepted);

        $actual = $target->getPoker();

        $this->assertCount(14, $actual);
        $this->assertSame($excepted, array_count_values($actual));
    }
}
