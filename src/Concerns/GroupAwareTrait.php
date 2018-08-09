<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;
use RuntimeException;

trait GroupAwareTrait
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
        $featureNames = $group->getFeaturesName();

        $this->assertAllFeaturesNotExist($featureNames);

        $this->appendFeatures($group->getFeatures());

        $this->updateFeatureGroupMapping($featureNames, $name);

        $this->groups[$name] = $group;

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
        $this->assertAllFeaturesExist($features);

        $this->groups[$name] = Group::create($this->normalizeFeatureMap($features), $processor);

        $this->updateFeatureGroupMapping($features, $name);

        return $this;
    }

    /**
     * @param string $name
     * @return Group
     * @throws InvalidArgumentException
     */
    public function getGroup($name)
    {
        if (!$this->isGroupExist($name)) {
            throw new InvalidArgumentException("Group '{$name}' is not found");
        }

        return $this->groups[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isGroupExist($name)
    {
        return array_key_exists($name, $this->groups);
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
            if (array_key_exists($featureName, $this->featureGroupMapping)) {
                $group = $this->featureGroupMapping[$featureName];
                throw new RuntimeException("Feature has been set for '{$group}'");
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
