<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

trait ProviderTrait
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
     * @param array $features
     * @return static
     */
    public function setFeatures(array $features)
    {
        $features = array_map(function ($feature) {
            if ($feature instanceof Feature) {
                return [
                    'result' => $feature->isActive(),
                ];
            }

            return $feature;
        }, $features);

        $this->features = array_merge($this->features, $features);

        return $this;
    }

    /**
     * @param array $groups
     * @return static
     */
    public function setGroups(array $groups)
    {
        $groups = array_map(function ($group) {
            if ($group instanceof Group) {
                return [
                    'list' => $group->getFeaturesName(),
                    'result' => $group->select(),
                ];
            }

            return $group;
        }, $groups);

        $this->groups = array_merge($this->groups, $groups);

        return $this;
    }
}
