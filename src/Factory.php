<?php

namespace MilesChou\Toggle;

use Noodlehaus\Config;

class Factory
{
    /**
     * @param array $config
     * @return Manager
     */
    public static function createFromArray($config)
    {
        $instance = new Manager();

        foreach ($config['feature'] as $name => $feature) {
            $instance->createFeature($name, $feature['return']);
        }

        foreach ($config['group'] as $name => $group) {
            $instance->createGroup($name, $group['list'], $group['return']);
        }

        return $instance;
    }

    /**
     * @param string $file
     * @return Manager
     */
    public static function createFromFile($file)
    {
        $config = (new Config($file))->all();

        return self::createFromArray($config);
    }
}
