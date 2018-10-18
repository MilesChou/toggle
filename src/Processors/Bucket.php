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
     * @param array $config
     * @return static
     */
    public static function create(array $config = [])
    {
        return new static($config);
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        if (null !== $config) {
            $this->setConfig($config);
        }
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->assertConfig($config);

        $this->bucket = $config['bucket'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\Bucket',
            'config' => [
                'bucket' => $this->bucket,
            ],
        ];
    }

    protected function handle($context)
    {
        return $this->bucket > mt_rand(0, 99);
    }

    /**
     * @param array $config
     */
    private function assertConfig($config)
    {
        if (!isset($config['bucket'])) {
            throw new InvalidArgumentException('Config bucket not found');
        }

        if (!is_int($config['bucket'])) {
            throw new InvalidArgumentException('Config bucket must be an int');
        }
    }
}
