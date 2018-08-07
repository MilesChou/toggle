<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;
use MilesChou\Toggle\Provider;

class ArrayProvider implements Provider
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
     * @param array $groups
     */
    public function __construct(array $features = [], array $groups = [])
    {
        $this->setFeatures($features);
        $this->setGroups($groups);
    }

    public function getFeatures()
    {
        return $this->features;
    }

    public function getGroups()
    {
        return $this->groups;
    }

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

    public function serialize()
    {
        return json_encode([
            'features' => $this->features,
            'groups' => $this->groups,
        ]);
    }

    public function unserialize($str)
    {
        $data = json_decode($str, true);

        $this->setFeatures($data['features']);
        $this->setGroups($data['groups']);

        return $this;
    }
}
