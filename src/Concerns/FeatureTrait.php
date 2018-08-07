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
        if ($feature instanceof Feature) {
            $this->features[$name] = $feature;
        } elseif (null === $feature || is_callable($feature)) {
            $this->features[$name] = Feature::create($feature);
        } else {
            throw new \InvalidArgumentException('The $feature must be Feature or callable.');
        }

        return $this;
    }

    public function cleanFeature()
    {
        $this->features = [];
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

    /**
     * @param string $name
     * @param callable|null $processor
     * @return static
     */
    public function withFeature($name, $processor = null)
    {
        $clone = clone $this;

        $clone->addFeature($name, $processor);

        return $clone;
    }
}
