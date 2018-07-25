<?php

namespace MilesChou\Toggle\Processors;

class RandomToggle
{
    public function __invoke()
    {
        return (bool)mt_rand(0, 1);
    }
}
