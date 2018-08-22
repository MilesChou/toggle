<?php

namespace MilesChou\Toggle\Serializers;

use MilesChou\Toggle\Contracts\SerializerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @uses Yaml
 */
class YamlSerializer implements SerializerInterface
{
    public function serialize($data)
    {
        return Yaml::dump($data, 4, 2);
    }

    public function deserialize($str)
    {
        return Yaml::parse($str);
    }
}
