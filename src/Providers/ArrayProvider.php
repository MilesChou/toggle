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
                return $feature->isActive();
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

    public function serialize($type = self::SERIALIZE_TYPE_JSON)
    {
        if (self::SERIALIZE_TYPE_JSON === $type) {
            return json_encode([
                'features' => $this->features,
                'groups' => $this->groups,
            ]);
        }

        if (self::SERIALIZE_TYPE_NATIVE === $type) {
            return serialize($this);
        }

        throw new \InvalidArgumentException("Unknown type: {$type}");
    }

    public function unserialize($str, $type = self::SERIALIZE_TYPE_JSON)
    {
        if (self::SERIALIZE_TYPE_JSON === $type) {
            $data = json_decode($str, true);

            $this->setFeatures($data['features']);
            $this->setGroups($data['groups']);

            return $this;
        }

        if (self::SERIALIZE_TYPE_NATIVE === $type) {
            return unserialize($str);
        }

        throw new \InvalidArgumentException("Unknown type: {$type}");
    }
}
