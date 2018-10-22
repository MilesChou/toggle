<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\ProviderInterface;
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
    public function deserialize($str, ProviderInterface $provider)
    {
        $provider->fill(json_decode($str, true));

        return $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(ProviderInterface $provider)
    {
        return json_encode($provider->toArray());
    }
}
