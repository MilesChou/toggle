<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Simplify\ToggleInterface as SimplifyToggleInterface;

interface ToggleInterface extends SimplifyToggleInterface
{
    /**
     * @param string $name
     * @param FeatureInterface $feature
     * @return static
     */
    public function add($name, FeatureInterface $feature);

    /**
     * @param string $name
     * @return FeatureInterface
     */
    public function feature($name);
}
