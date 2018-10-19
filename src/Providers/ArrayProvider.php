<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Concerns\SerializerAwareTrait;
use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

class ArrayProvider implements ProviderInterface
{
    use SerializerAwareTrait;

    /**
     * @var array
     */
    private $features = [];

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * @param array $data
     * @param Context|null $context
     * @return static
     */
    public function fill(array $data, $context = null)
    {
        if (isset($data['feature'])) {
            $this->setFeatures($data['feature'], $context);
        }

        if (isset($data['group'])) {
            $this->setGroups($data['group'], $context);
        }

        return $this;
    }

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
    public function setGroups(array $groups, $context = null)
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'feature' => $this->getFeatures(),
            'group' => $this->getGroups(),
        ];
    }
}