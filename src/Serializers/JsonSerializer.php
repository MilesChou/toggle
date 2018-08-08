<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    public function serialize($data)
    {
        return json_encode($data);
    }

    public function unserialize($str)
    {
        return json_decode($str, true);
    }
}
