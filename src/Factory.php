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
        $toggle = new Toggle();

        if (empty($config)) {
            return $toggle;
        }

        foreach ($config as $name => $item) {
            $item = $this->normalizeConfigItem($item);

            $toggle->create($name, $item['processor'], $item['params'], $item['staticResult']);
        }

        return $toggle;
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

        if (!static::hasStaticResult($config)) {
            $config['staticResult'] = null;
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
