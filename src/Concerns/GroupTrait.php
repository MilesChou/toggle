<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

trait GroupTrait
{
    /**
     * @var Group[]
     */
    private $groups = [];

    /**
     * @var array
     */
    private $featureGroupMapping = [];

    /**
     * @param string $name
     * @param Group $group
     * @return static
     */
    public function addGroup($name, Group $group)
    {
        $this->groups[$name] = $group;

        $this->updateFeatureGroupMapping($group->getFeaturesName(), $name);

        return $this;
    }

    public function cleanGroup()
    {
        $this->groups = [];
        $this->featureGroupMapping = [];
    }

    /**
     * @param string $name
     * @param array $features
     * @param callable|string|null $processor
     * @return static
     */
    public function createGroup($name, array $features, $processor = null)
    {
        $this->groups[$name] = Group::create($this->normalizeFeatureMap($features), $processor);

        $this->updateFeatureGroupMapping($features, $name);

        return $this;
    }

    /**
     * @param string $name
     */
    public function removeGroup($name)
    {
        unset($this->groups[$name]);
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

    /**
     * @param array $featureNames
     * @param string $groupName
     */
    protected function updateFeatureGroupMapping(array $featureNames, $groupName)
    {
        foreach ($featureNames as $featureName) {
            $this->featureGroupMapping[$featureName] = $groupName;
        }
    }
}
