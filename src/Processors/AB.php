<?php

namespace MilesChou\Toggle\Processors;

use InvalidArgumentException;

class AB extends Processor
{
    /**
     * @var array
     */
    private $buckets = [];

    /**
     * @var array
     */
    private $poker = [];

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
     * @return array
     */
    public function getPoker()
    {
        return $this->poker;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->assertConfig($config);

        $this->buckets = $config;

        $this->poker = [];

        foreach ($this->buckets as $feature => $bucket) {
            $featurePoker = array_fill(0, $bucket, $feature);
            $this->poker = array_merge($this->poker, $featurePoker);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\AB',
            'config' => $this->buckets,
        ];
    }

    protected function handle($context)
    {
        return $this->poker[array_rand($this->poker)];
    }

    /**
     * @param array $config
     */
    private function assertConfig($config)
    {
        if (!is_array($config)) {
            throw new InvalidArgumentException('Config item must be an array');
        }
    }
}
