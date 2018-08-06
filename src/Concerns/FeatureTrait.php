<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Feature;

trait FeatureTrait
{
    /**
     * @var Feature[]
     */
    private $features = [];

    /**
     * @param string $name
     * @param callable|null $processor
     * @return static
     */
    public function addFeature($name, $processor = null)
    {
        $this->features[$name] = Feature::create($processor);

        return $this;
    }

    /**
     * @param string $name
     */
    public function removeFeature($name)
    {
        unset($this->features[$name]);
    }
}
