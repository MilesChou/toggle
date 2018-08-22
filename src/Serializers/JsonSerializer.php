<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\SerializerInterface;

/**
 * @uses json_encode()
 * @uses json_decode()
 */
class JsonSerializer implements SerializerInterface
{
    public function serialize($data)
    {
        return json_encode($data);
    }

    public function deserialize($str)
    {
        return json_decode($str, true);
    }
}
