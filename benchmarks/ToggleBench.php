<?php

namespace Benchmarks;

use MilesChou\Toggle\Manager;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

class ToggleBench
{
    /**
     * @Revs(100000)
     */
    public function benchManagerUsingAdd()
    {
        $target = new Manager();

        $target->addFeature('f1')
            ->addFeature('f2')
            ->addFeature('f3')
            ->addGroup('foo', [
                'f1',
                'f2',
                'f3',
            ], function () {
                return 'f1';
            })
            ->select('foo');
    }

    /**
     * @Revs(100000)
     */
    public function benchManagerUsingWith()
    {
        $target = new Manager();

        $target->withFeature('f1')
            ->withFeature('f2')
            ->withFeature('f3')
            ->withGroup('foo', [
                'f1',
                'f2',
                'f3',
            ], function () {
                return 'f1';
            })
            ->select('foo');
    }
}