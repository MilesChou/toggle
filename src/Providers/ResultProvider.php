<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

class ResultProvider extends Provider
{
    /**
     * @param string $name
     * @param bool $result
     * @return static
     */
    public function feature($name, $result)
    {
        $this->features[$name] = $result;

        return $this;
    }

    /**
     * @param string $name
     * @param string $result
     * @return static
     */
    public function group($name, $result)
    {
        $this->groups[$name] = $result;

        return $this;
    }

    /**
     * @param array $features
     * @param Context|null $context
     * @return static
     */
    final public function setFeatures(array $features, $context = null)
    {
        $this->features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return $feature->isActive($context);
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
                return $group->select($context);
            }

            return $group;
        }, $groups);

        return $this;
    }
}
