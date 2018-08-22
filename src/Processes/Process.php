<?php

namespace MilesChou\Toggle\Processes;

use MilesChou\Toggle\Context;

abstract class Process
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param Context|null $context
     * @return mixed
     */
    final public function __invoke(Context $context = null)
    {
        return $this->handle($context);
    }

    /**
     * @param array $config
     * @return static
     */
    public static function retrieve(array $config)
    {
        $class = $config['class'];

        /** @var static $instance */
        $instance = new $class();
        $instance->setConfig($config['config']);

        return $instance;
    }

    /**
     * @param array $config
     */
    abstract public function setConfig(array $config);

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @param Context $context
     * @return mixed
     */
    abstract protected function handle($context);
}
