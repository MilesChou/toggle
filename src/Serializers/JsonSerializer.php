<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Concerns\SerializerTrait;
use MilesChou\Toggle\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    use SerializerTrait;

    /**
     * @param array $features
     * @param array $groups
     */
    public function __construct(array $features = [], array $groups = [])
    {
        $this->setFeatures($features);
        $this->setGroups($groups);
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
