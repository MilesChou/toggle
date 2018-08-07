<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Concerns\ProviderTrait;
use MilesChou\Toggle\Provider;

class ArrayProvider implements Provider
{
    use ProviderTrait;

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
