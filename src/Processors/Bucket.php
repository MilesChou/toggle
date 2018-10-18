<?php

namespace MilesChou\Toggle\Processors;

use InvalidArgumentException;

class Bucket extends Processor
{
    /**
     * @var int
     */
    private $percentage = 0;

    /**
     * @param int $percentage
     * @return static
     */
    public static function create($percentage)
    {
        return new static($percentage);
    }

    /**
     * @param int $percentage
     */
    public function __construct($percentage = null)
    {
        if (null !== $percentage) {
            $this->setConfig(['percentage' => $percentage]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setConfig($config)
    {
        $this->assertConfig($config);

        $this->percentage = $config['percentage'];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\Bucket',
            'percentage' => $this->percentage,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handle($context)
    {
        return $this->percentage > mt_rand(0, 99);
    }

    /**
     * @param mixed $config
     */
    private function assertConfig($config)
    {
        if (!isset($config['percentage'])) {
            throw new InvalidArgumentException('Percentage must be an int');
        }

        if (!is_int($config['percentage'])) {
            throw new InvalidArgumentException('Percentage must be an int');
        }

        if ($config['percentage'] < 0 || $config['percentage'] > 100) {
            throw new InvalidArgumentException('Percentage is out of range');
        }
    }
}
