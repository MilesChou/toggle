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
    public function deserialize($str)
    {
        return Yaml::parse($str);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($array)
    {
        return Yaml::dump($array, 4, 2);
    }
}
