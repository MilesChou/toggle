<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\SerializerAwareTrait;
use MilesChou\Toggle\Contracts\DataProviderInterface;

class DataProvider implements DataProviderInterface
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
     * @param array $features
     * @param array $groups
     */
    public function __construct(array $features = [], array $groups = [])
    {
        $this->setFeatures($features);
        $this->setGroups($groups);
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
        $features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    'r' => $feature->isActive($context),
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
                    'l' => $group->getFeaturesName(),
                    'r' => $group->select($context),
                ];
            }

            return $group;
        }, $groups);

        $this->groups = array_merge($this->groups, $groups);

        return $this;
    }

    /**
     * @param mixed $data
     * @return static
     */
    protected function retrieveRestoreData($data)
    {
        $this->setFeatures($data['f']);
        $this->setGroups($data['g']);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function retrieveStoreData()
    {
        return [
            'f' => $this->getFeatures(),
            'g' => $this->getGroups(),
        ];
    }
}
