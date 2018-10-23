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
     * @param Feature $feature
     * @return static
     */
    public function addFeature(Feature $feature)
    {
        $name = $feature->getName();

        if ($this->isFeatureExist($name)) {
            throw new RuntimeException("Feature '{$name}' is exist");
        }

        $this->features[$name] = $feature;

        return $this;
    }

    /**
     * @param array $features
     * @return static
     */
    public function appendFeatures(array $features)
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }

        return $this;
    }

    public function cleanFeatures()
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
        return $this->addFeature(Feature::create($name, $processor, $params));
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
     * @return bool
     */
    public function hasFeature($name)
    {
        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     * @return Feature
     * @throws InvalidArgumentException
     */
    public function getFeature($name)
    {
        if (!$this->isFeatureExist($name)) {
            throw new RuntimeException("Feature '{$name}' is not found");
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
     * @param array $names
     * @return array
     */
    public function getFeaturesByName(array $names)
    {
        return array_reduce($names, function ($carry, $name) {
            $carry[$name] = $this->getFeature($name);

            return $carry;
        }, []);
    }

    /**
     * @return array
     */
    public function getFeaturesName()
    {
        return array_keys($this->features);
    }

    /**
     * @param Feature|string $name
     * @return bool
     */
    public function isFeatureExist($name)
    {
        if ($name instanceof Feature) {
            $name = $name->getName();
        }

        return array_key_exists($name, $this->features);
    }

    /**
     * @param string $name
     */
    public function removeFeature($name)
    {
        unset($this->features[$name], $this->featuresPreserveResult[$name]);
    }

    /**
     * @param array $features
     * @return static
     */
    public function setFeatures(array $features)
    {
        $this->cleanFeatures();
        $this->appendFeatures($features);

        return $this;
    }
}
