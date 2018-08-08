<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Feature;
use RuntimeException;

trait FeatureTrait
{
    /**
     * @var Feature[]
     */
    private $features = [];

    /**
     * @param string $name
     * @param Feature $feature
     * @return static
     */
    public function addFeature($name, Feature $feature)
    {
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

    /**
     * @param array $featureNames
     * @throws RuntimeException
     */
    protected function assertFeatureExist($featureNames)
    {
        foreach ($featureNames as $featureName) {
            if (!array_key_exists($featureName, $this->features)) {
                throw new RuntimeException("Feature '{$featureName}' is not set");
            }
        }
    }
}
