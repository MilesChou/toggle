<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Contracts\SerializerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @uses \Symfony\Component\Yaml\Yaml
 */
class YamlSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function deserialize($str, ProviderInterface $provider)
    {
        $provider->fill(Yaml::parse($str));

        return $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(ProviderInterface $provider)
    {
        return Yaml::dump($provider->toArray(), 4, 2);
    }
}
