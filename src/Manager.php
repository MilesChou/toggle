<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Traits\FacadeTrait;

class Manager
{
    use FacadeTrait;

    /**
     * @var array
     */
    private $features = [];

    /**
     * @param string $featureName
     */
    public function isActive($featureName)
    {
        if (!array_key_exists($featureName, $this->features)) {
            throw new \InvalidArgumentException("Feature '{$featureName}' is not found");
        }
    }
}
