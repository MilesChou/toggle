<?php

namespace Benchmarks;

use MilesChou\Toggle\Context;
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

        $target->createFeature('f1')
            ->createFeature('f2')
            ->createFeature('f3')
            ->createGroup('foo', ['f1', 'f2', 'f3'], 'f1')
            ->select('foo');
    }

    /**
     * @Revs(10000)
     * @Iterations(3)
     */
    public function benchFeatureIsActive()
    {
        $target = Feature::create('whatever', function (Context $context) {
            $id = $context->getParam('id');

            return 0 === $id % 2;
        });

        $target->isActive(Context::create(['id' => 1]));
        $target->isActive(Context::create(['id' => 2]));
    }
}
