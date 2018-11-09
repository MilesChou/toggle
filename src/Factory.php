<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Processors\Processor;
use Noodlehaus\Config;

class Factory
{
    private static function hasStaticResult($config)
    {
        return isset($config['staticResult']) && is_bool($config['staticResult']);
    }

    /**
     * @param array $config
     * @return Toggle
     */
    public function createFromArray(array $config)
    {
        $instance = new Toggle();

        if (empty($config)) {
            return $instance;
        }

        foreach ($config as $name => $item) {
            $item = $this->normalizeConfigItem($item);

            $instance->createFeature($name, $item['processor'], $item['params']);

            if (static::hasStaticResult($item)) {
                $instance->feature($name)->setStaticResult($item['staticResult']);
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
