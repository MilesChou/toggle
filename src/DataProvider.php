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
        $this->features = array_map(function ($feature) use ($context) {
            if ($feature instanceof Feature) {
                return [
                    static::FEATURE_PARAMS => $feature->getParams(),
                    static::FEATURE_RETURN => $feature->isActive($context),
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
                    static::GROUP_PARAMS => $group->getParams(),
                    static::GROUP_LIST => $group->getFeaturesName(),
                    static::GROUP_RETURN => $group->select($context),
                ];
            }

            return $group;
        }, $groups);

        return $this;
    }

    /**
     * @param mixed $data
     * @return static
     */
    protected function retrieveRestoreData($data)
    {
        $this->setFeatures($data[static::FEATURE]);
        $this->setGroups($data[static::GROUP]);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function retrieveStoreData()
    {
        return [
            static::FEATURE => $this->getFeatures(),
            static::GROUP => $this->getGroups(),
        ];
    }
}
