<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Concerns\ProviderTrait;
use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Feature;
use MilesChou\Toggle\Group;

abstract class Provider implements ProviderInterface
{
    /**
     * @var array
     */
    protected $features = [];

    /**
     * @var array
     */
    protected $groups = [];

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
