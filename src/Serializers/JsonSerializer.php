<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\SerializerInterface;

/**
 * @uses json_encode()
 * @uses json_decode()
 */
class JsonSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function deserialize($str)
    {
        return json_decode($str, true);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data)
    {
        return json_encode($data);
    }
}
