<?php

namespace Tests\Toggle\Fixtures;

use MilesChou\Toggle\Contracts\ProcessorInterface;

class DummyProcessor implements ProcessorInterface
{
    /**
     * @var mixed
     */
    private $config;

    public function __invoke()
    {
        // Always return true
        return true;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function toArray(): array
    {
        $config = $this->config;
        $config['class'] = __CLASS__;

        return $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }
}
