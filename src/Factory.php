<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Processors\Processor;
use Noodlehaus\Config;

class Factory
{
    private static function hasStaticResult($config)
    {
        return array_key_exists('hasStaticResult', $config) &&
            array_key_exists('staticResult', $config) &&
            true === $config['hasStaticResult'];
    }

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

            if (static::hasStaticResult($feature)) {
                $instance->feature($name)->staticResult($feature['staticResult']);
            }
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

        $config['processor'] = $this->resolveProcessorConfig($config['processor']);

        return $config;
    }

    /**
     * @param array $config
     * @return mixed
     */
    private function resolveProcessorConfig($config)
    {
        if (isset($config['class'])) {
            return Processor::retrieve($config);
        }

        return $config;
    }
}
