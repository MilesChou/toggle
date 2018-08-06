<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

trait GroupTrait
{
    /**
     * @var Group[]
     */
    private $group = [];

    /**
     * @var array
     */
    private $featureGroupMapping = [];

    /**
     * @param string $name
     * @param array $features
     * @param callable|null $processor
     * @return static
     */
    public function addGroup($name, array $features, $processor = null)
    {
        $featureMap = $this->normalizeFeatureMap($features);
        $this->group[$name] = Group::create($featureMap, $processor);

        array_map(function ($featureName) use ($name) {
            $this->featureGroupMapping[$featureName] = $name;
        }, $features);

        return $this;
    }

    /**
     * @param string $name
     */
    public function removeGroup($name)
    {
        unset($this->group[$name]);
    }

    /**
     * @param array $features
     * @return Feature[]
     */
    protected function normalizeFeatureMap(array $features)
    {
        $featureInstances = array_map(function ($featureName) {
            if (!array_key_exists($featureName, $this->features)) {
                throw new \RuntimeException("Feature '{$featureName}' is not set");
            }

            if (array_key_exists($featureName, $this->featureGroupMapping)) {
                $group = $this->featureGroupMapping[$featureName];
                throw new \RuntimeException("Feature has been set for '{$group}'");
            }

            return $this->features[$featureName];
        }, $features);

        return array_combine($features, $featureInstances);
    }
}
