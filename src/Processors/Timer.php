<?php

namespace MilesChou\Toggle\Processors;

use Carbon\Carbon;
use InvalidArgumentException;

class Timer extends Processor
{
    /**
     * @var mixed
     */
    private $default;

    /**
     * @var bool
     */
    private $then = true;

    /**
     * @var array
     */
    private $timer = [];

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
     * {@inheritdoc}
     */
    public function setConfig($config)
    {
        $this->assertConfig($config);

        $this->then = isset($config['then'])
            ? (bool)$config['then']
            : true;

        $this->default = isset($config['default'])
            ? $config['default']
            : null;

        $this->timer = $config['timer'];

        if ($this->then) {
            krsort($this->timer);
        } else {
            ksort($this->timer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'class' => 'MilesChou\\Toggle\\Processors\\Timer',
            'config' => [
                'default' => $this->default,
                'timer' => $this->timer,
                'then' => $this->then,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function handle($context)
    {
        foreach ($this->timer as $time => $return) {
            if (!is_numeric($time)) {
                $time = Carbon::parse($time)->timestamp;
            }

            if ($this->then && !Carbon::createFromTimestamp($time)->isFuture()) {
                return $return;
            }

            if (!$this->then && !Carbon::createFromTimestamp($time)->isPast()) {
                return $return;
            }
        }

        return $this->default;
    }

    /**
     * @param array $config
     */
    private function assertConfig($config)
    {
        if (!is_array($config)) {
            throw new InvalidArgumentException('Config item must be an array');
        }

        if (!array_key_exists('timer', $config)) {
            throw new InvalidArgumentException("Config item must include key 'timer'");
        }
    }
}
