<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;
use RuntimeException;

/**
 * @uses FeatureAwareTrait
 */
trait GroupAwareTrait
{
    /**
     * @var Group[]
     */
    private $groups = [];

    /**
     * @var array
     */
    private $groupsPreserveResult = [];

    /**
     * @var array
     */
    private $featureGroupMapping = [];

    /**
     * @param string $feature
     * @param string $group
     * @return static
     */
    public function addFeatureGroupMapping($feature, $group)
    {
        $this->featureGroupMapping[$feature] = $group;
        return $this;
    }

    /**
     * @param Group $group
     * @return static
     */
    public function addGroup(Group $group)
    {
        $features = $group->getFeatures();
        $name = $group->getName();

        $this->appendFeatures($features);
        $this->updateFeatureGroupMapping($features, $name);

        $this->groups[$name] = $group;

        return $this;
    }

    public function cleanGroup()
    {
        $this->groups = [];
        $this->groupsPreserveResult = [];
        $this->featureGroupMapping = [];
    }

    /**
     * @param string $name
     * @param array $features
     * @param callable|string|null $processor
     * @param array $params
     * @return static
     */
    public function createGroup($name, array $features, $processor = null, array $params = [])
    {
        $featureInstances = $this->getFeaturesByName($features);

        $this->updateFeatureGroupMapping($featureInstances, $name);

        $this->groups[$name] = Group::create($name, $featureInstances, $processor, $params);

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
     * @param string $featureName
     * @return string
     * @throws InvalidArgumentException
     */
    public function getMappingGroup($featureName)
    {
        if (!$this->isMappingExist($featureName)) {
            throw new InvalidArgumentException("Feature '{$featureName}' is not in mapping");
        }

        return $this->featureGroupMapping[$featureName];
    }

    /**
     * Alias of getGroup()
     *
     * @param string $name
     * @return Group
     */
    public function group($name)
    {
        return $this->getGroup($name);
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
     * @param string $featureName
     * @return bool
     */
    public function isMappingExist($featureName)
    {
        return array_key_exists($featureName, $this->featureGroupMapping);
    }

    /**
     * @param string $name
     */
    public function removeGroup($name)
    {
        unset($this->groups[$name]);
    }

    /**
     * @param Feature[] $features
     * @param string $groupName
     */
    protected function updateFeatureGroupMapping(array $features, $groupName)
    {
        foreach ($features as $feature) {
            $name = $feature->getName();

            if ($this->isMappingExist($name)) {
                $group = $this->getMappingGroup($name);
                throw new RuntimeException("Feature has been set for '{$group}'");
            }

            $this->addFeatureGroupMapping($name, $groupName);
        }
    }
}
