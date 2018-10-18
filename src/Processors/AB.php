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
     * @param array $buckets
     * @return static
     */
    public static function create(array $buckets = [])
    {
        return new static($buckets);
    }

    /**
     * @param array $buckets
     */
    public function __construct(array $buckets = null)
    {
        if (null !== $buckets) {
            $this->setConfig(['buckets' => $buckets]);
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
     * {@inheritdoc}
     */
    public function setConfig($config)
    {
        $this->assertConfig($config);

        $this->buckets = $config['buckets'];

        $this->poker = [];

        foreach ($this->buckets as $feature => $bucket) {
            $featurePoker = array_fill(0, $bucket, $feature);
            $this->poker = array_merge($this->poker, $featurePoker);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\AB',
            'buckets' => $this->buckets,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handle($context)
    {
        return $this->poker[array_rand($this->poker)];
    }

    /**
     * @param array $config
     */
    private function assertConfig($config)
    {
        if (!isset($config['buckets'])) {
            throw new InvalidArgumentException('Key `buckets` is undefined');
        }

        if (!is_array($config['buckets'])) {
            throw new InvalidArgumentException('`buckets` must be an array');
        }
    }
}
