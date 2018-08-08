<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

trait SerializerTrait
{
    /**
     * @var array
     */
    private $features = [];

    /**
     * @var array
     */
    private $groups = [];

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
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    public function setFeatures(array $features, $context = null)
    {
        $features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    'result' => $feature->isActive($context),
                ];
            }

            return $feature;
        }, $features);

        $this->features = array_merge($this->features, $features);

        return $this;
    }

    /**
     * @param array $groups
     * @param Context|null $context
     * @return static
     */
    public function setGroups(array $groups, $context = null)
    {
        $groups = array_map(function ($group) use ($context) {
            if ($group instanceof Group) {
                return [
                    'list' => $group->getFeaturesName(),
                    'result' => $group->select($context),
                ];
            }

            return $group;
        }, $groups);

        $this->groups = array_merge($this->groups, $groups);

        return $this;
    }
}
