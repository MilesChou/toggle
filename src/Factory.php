<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Processors\Processor;
use Noodlehaus\Config;

class Factory
{
    /**
     * @param array $config
     * @return Toggle
     */
    public function createFromArray($config)
    {
        $instance = new Toggle();

        $config = $this->normalizeConfig($config);

        foreach ($config['feature'] as $name => $feature) {
            $feature = $this->normalizeConfigItem($feature);

            $instance->createFeature($name, $feature['processor'], $feature['params']);
        }

        foreach ($config['group'] as $name => $group) {
            $group = $this->normalizeConfigItem($group);

            $instance->createGroup($name, $group['list'], $group['processor'], $group['params']);
        }

        return $instance;
    }

    /**
     * @param string $file
     * @return Toggle
     * @throws \Noodlehaus\Exception\EmptyDirectoryException
     */
    public function createFromFile($file)
    {
        $config = (new Config($file))->all();

        return $this->createFromArray($config);
    }

    /**
     * @param array $config
     * @return array
     */
    private function normalizeConfig($config)
    {
        if (!isset($config['feature'])) {
            $config['feature'] = [];
        }

        if (!isset($config['group'])) {
            $config['group'] = [];
        }

        return $config;
    }

    /**
     * @param array $config
     * @return array
     */
    private function normalizeConfigItem($config)
    {
        if (!isset($config['processor'])) {
            $config['processor'] = null;
        }

        if (!isset($config['params'])) {
            $config['params'] = [];
        }

        $config['processor'] = $this->resolveProcessor($config['processor']);

        return $config;
    }

    /**
     * @param array $config
     * @return
     */
    private function resolveProcessor($config)
    {
        if (isset($config['class'])) {
            return Processor::retrieve($config);
        }

        return $config;
    }
}
