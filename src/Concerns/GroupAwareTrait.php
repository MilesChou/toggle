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
     * @param Feature $feature
     * @param Group $group
     * @return static
     */
    public function addFeatureGroupMapping($feature, $group)
    {
        $feature->setGroup($group);

        $this->featureGroupMapping[$feature->getName()] = $group->getName();
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

        $this->groups[$name] = $group;
        $this->appendFeatures($features);
        $this->updateFeatureGroupMapping($features, $group);

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
     * @param array $featuresName
     * @param callable|string|null $processor
     * @param array $params
     * @return static
     */
    public function createGroup($name, array $featuresName, $processor = null, array $params = [])
    {
        $features = $this->getFeaturesByName($featuresName);

        $group = Group::create($name, $features, $processor, $params);

        $this->groups[$name] = $group;
        $this->updateFeatureGroupMapping($features, $group);

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
     * @param Group $group
     */
    protected function updateFeatureGroupMapping(array $features, Group $group)
    {
        foreach ($features as $feature) {
            $name = $feature->getName();

            if ($this->isMappingExist($name)) {
                $groupName = $this->getMappingGroup($name);
                throw new RuntimeException("Feature has been set for '{$groupName}'");
            }

            $this->addFeatureGroupMapping($feature, $group);
        }
    }
}
