<?php

namespace MilesChou\Toggle\Processors;

use InvalidArgumentException;

abstract class Processor
{
    /**
     * @param array $context
     * @return mixed
     */
    final public function __invoke(array $context = [])
    {
        return $this->handle($context);
    }

    /**
     * @param array $config
     * @return static
     */
    public static function retrieve(array $config)
    {
        if (!isset($config['class'])) {
            throw new InvalidArgumentException("Retrieve process must have 'class' key");
        }

        $class = $config['class'];
        unset($config['class']);

        /** @var static $instance */
        $instance = new $class();
        $instance->setConfig($config);

        return $instance;
    }

    /**
     * @param mixed $config
     */
    abstract public function setConfig($config);

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @param array $context
     * @return mixed
     */
    abstract protected function handle($context);
}
