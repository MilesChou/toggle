<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Processors\Processor;
use MilesChou\Toggle\Providers\DataProvider;
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

            $toggle->create($name, $item['processor'], $item['params']);

            if (static::hasStaticResult($item)) {
                $toggle->feature($name)->result($item['staticResult']);
            }
        }

        return $toggle;
    }

    /**
     * @param DataProvider $dataProvider
     * @return Toggle
     */
    public function createFromDataProvider(DataProvider $dataProvider)
    {
        return $this->createFromArray(array_map(function ($feature) {
            $feature['processor'] = $feature['return'];

            return $feature;
        }, $dataProvider->toArray()));
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
     * @param Toggle $toggle
     * @param DataProvider|null $dataProvider
     * @return DataProvider
     */
    public function transferToDataProvider(Toggle $toggle, DataProvider $dataProvider = null)
    {
        if (null === $dataProvider) {
            $dataProvider = new DataProvider();
        }

        return $dataProvider
            ->fill($toggle->all(), $toggle->getContext());
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
