<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Runner;
use RuntimeException;

interface RunnerInterface
{
    /**
     * @param callable $positive
     * @param callable|null $negative
     * @return mixed
     */
    public function then(callable $positive, callable $negative = null);
}
