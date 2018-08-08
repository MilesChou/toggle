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
            'f' => $this->features,
            'g' => $this->groups,
        ]);
    }

    public function unserialize($str)
    {
        $data = json_decode($str, true);

        $this->setFeatures($data['f']);
        $this->setGroups($data['g']);

        return $this;
    }
}
