<?php

namespace MilesChou\Toggle\Processors;

use InvalidArgumentException;

class Bucket extends Processor
{
    /**
     * @var int
     */
    private $bucket = 0;

    /**
     * @param int $config
     * @return static
     */
    public static function create($config)
    {
        return new static($config);
    }

    /**
     * @param int $config
     */
    public function __construct($config = null)
    {
        if (null !== $config) {
            $this->setConfig($config);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConfig($config)
    {
        $this->assertConfig($config);

        $this->bucket = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\Bucket',
            'config' => $this->bucket,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handle($context)
    {
        return $this->bucket > mt_rand(0, 99);
    }

    /**
     * @param mixed $config
     */
    private function assertConfig($config)
    {
        if (!is_int($config)) {
            throw new InvalidArgumentException('Config must be an int');
        }

        if ($config < 0 || $config > 100) {
            throw new InvalidArgumentException('Config is out of range');
        }
    }
}
