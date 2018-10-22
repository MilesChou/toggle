<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

class DataProvider extends Provider
{
    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    final public function setFeatures(array $features, $context = null)
    {
        $this->features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    'params' => $feature->getParams(),
                    'return' => $feature->isActive($context),
                ];
            }

            return $feature;
        }, $features);

        return $this;
    }

    /**
     * @param array $groups
     * @param Context|null $context
     * @return static
     */
    final public function setGroups(array $groups, $context = null)
    {
        $this->groups = array_map(function ($group) use ($context) {
            if ($group instanceof Group) {
                return [
                    'params' => $group->getParams(),
                    'list' => $group->getFeaturesName(),
                    'return' => $group->select($context),
                ];
            }

            return $group;
        }, $groups);

        return $this;
    }
}
