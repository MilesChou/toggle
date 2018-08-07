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
     * @param Feature|callable|null|bool $feature
     * @return static
     */
    public function addFeature($name, $feature = null)
    {
        if (!($feature instanceof Feature)) {
            throw new \InvalidArgumentException('The param $feature must be Feature instance');
        }

        $this->features[$name] = $feature;

        return $this;
    }

    public function cleanFeature()
    {
        $this->features = [];
    }

    /**
     * @param string $name
     * @param callable|bool|null $callable
     * @return static
     */
    public function createFeature($name, $callable = null)
    {
        $this->features[$name] = Feature::create($callable);

        return $this;
    }

    /**
     * @return array
     */
    public function getFeaturesName()
    {
        return array_keys($this->features);
    }

    /**
     * @param string $name
     */
    public function removeFeature($name)
    {
        unset($this->features[$name]);
    }
}
