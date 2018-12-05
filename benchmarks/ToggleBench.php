<?php

namespace Benchmarks;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Toggle;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class ToggleBench
{
    /**
     * @Revs(10000)
     * @Iterations(3)
     */
    public function benchManagerUsingGroup()
    {
        $target = new Toggle();

        $target->create('f1')
            ->create('f2')
            ->create('f3');
    }

    /**
     * @Revs(10000)
     * @Iterations(3)
     */
    public function benchFeatureIsActive()
    {
        $target = Feature::create(function ($context) {
            $id = $context['id'];

            return 0 === $id % 2;
        });

        $target->isActive(['id' => 1]);
        $target->isActive(['id' => 2]);
    }
}
