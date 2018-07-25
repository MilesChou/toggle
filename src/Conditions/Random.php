<?php

namespace MilesChou\Toggle\Conditions;

class Random
{
    public function __invoke()
    {
        return (bool)mt_rand(0, 1);
    }
}
