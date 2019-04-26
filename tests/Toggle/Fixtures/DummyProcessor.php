<?php

namespace Tests\Toggle\Fixtures;

use MilesChou\Toggle\Simplify\ProcessorInterface;

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

    /**
     * {@inheritdoc}
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
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
