<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Processes\Process;
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
            if (isset($feature['processor']['class'])) {
                $feature['processor'] = Process::retrieve($feature['processor']);
            }

            if (!isset($feature['processor'])) {
                $feature['processor'] = [];
            }

            if (!isset($feature['params'])) {
                $feature['params'] = [];
            }

            $instance->createFeature($name, $feature['processor'], $feature['params']);
        }

        foreach ($config['group'] as $name => $group) {
            if (isset($group['processor']['class'])) {
                $group['processor'] = Process::retrieve($group['processor']);
            }

            if (!isset($group['processor'])) {
                $group['processor'] = [];
            }

            if (!isset($group['params'])) {
                $group['params'] = [];
            }

            $instance->createGroup($name, $group['list'], $group['processor'], $group['params']);
        }

        return $instance;
    }

    /**
     * @param string $file
     * @return Manager
     * @throws \Noodlehaus\Exception\EmptyDirectoryException
     */
    public static function createFromFile($file)
    {
        $config = (new Config($file))->all();

        return self::createFromArray($config);
    }
}
