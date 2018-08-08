<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\SerializerInterface;

class JsonDataProvider extends AbstractDataProvider implements SerializerInterface
{
    public function serialize()
    {
        return json_encode([
            'f' => $this->getFeatures(),
            'g' => $this->getGroups(),
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
