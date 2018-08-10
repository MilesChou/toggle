<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use RuntimeException;

trait FeatureAwareTrait
{
    /**
     * @var Feature[]
     */
    private $features = [];

    /**
     * @var array
     */
    private $featuresPreserveResult = [];

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

    /**
     * @param array $features
     * @return static
     */
    public function appendFeatures(array $features)
    {
        $this->features = array_merge($this->features, $features);

        return $this;
    }

    public function cleanFeature()
    {
        $this->features = [];
        $this->featuresPreserveResult = [];
    }

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @return static
     */
    public function createFeature($name, $processor = null, array $params = [])
    {
        $this->features[$name] = Feature::create($name, $processor, $params);

        return $this;
    }

    /**
     * Alias of getFeature()
     *
     * @param string $name
     * @return Feature
     */
    public function feature($name)
    {
        return $this->getFeature($name);
    }

    /**
     * @param string $name
     * @return Feature
     * @throws InvalidArgumentException
     */
    public function getFeature($name)
    {
        if (!$this->isFeatureExist($name)) {
            throw new InvalidArgumentException("Feature '{$name}' is not found");
        }

        return $this->features[$name];
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
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
     * @return bool
     */
    public function isFeatureExist($name)
    {
        return array_key_exists($name, $this->features);
    }

    /**
     * @param array $featureNames
     * @return bool
     */
    public function isAllFeaturesExist(array $featureNames)
    {
        return array_reduce($featureNames, function ($carry, $name) {
            return $carry && $this->isFeatureExist($name);
        }, true);
    }

    /**
     * @param array $featureNames
     * @return bool
     */
    public function isAllFeaturesNotExist(array $featureNames)
    {
        return array_reduce($featureNames, function ($carry, $name) {
            return $carry && !$this->isFeatureExist($name);
        }, true);
    }

    /**
     * @param string $name
     */
    public function removeFeature($name)
    {
        unset($this->features[$name]);
    }

    /**
     * @param array $features
     * @return static
     */
    public function setFeatures(array $features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @param array|string $featureNames
     * @throws RuntimeException
     */
    protected function assertAllFeaturesExist($featureNames)
    {
        if (!$this->isAllFeaturesExist($featureNames)) {
            throw new RuntimeException('Some feature is not exist');
        }
    }

    /**
     * @param array $featureNames
     * @throws RuntimeException
     */
    protected function assertAllFeaturesNotExist($featureNames)
    {
        if (!$this->isAllFeaturesNotExist($featureNames)) {
            throw new RuntimeException('Some feature is exist');
        }
    }
}
