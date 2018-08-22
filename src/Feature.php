<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\NameAwareTrait;
use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Contracts\GroupInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;
use MilesChou\Toggle\Processes\Process;

class Feature implements FeatureInterface, ParameterAwareInterface
{
    use ParameterAwareTrait;
    use ProcessorAwareTrait;
    use NameAwareTrait;

    /**
     * @var GroupInterface
     */
    private $group;

    /**
     * @param string $name
     * @param callable|array|bool|null $processor
     * @param array $params
     * @return static
     */
    public static function create($name, $processor = null, array $params = [])
    {
        if (is_array($processor)) {
            $processor = Process::retrieve($processor);
        }

        if (null === $processor || is_bool($processor)) {
            $result = (bool)$processor;

            $processor = function () use ($result) {
                return $result;
            };
        }

        return new static($name, $processor, $params);
    }

    /**
     * @param string $name
     * @param callable $processor The callable will return bool
     * @param array $params
     */
    public function __construct($name, $processor, array $params = [])
    {
        $this->setName($name);
        $this->setProcessor($processor);
        $this->setParams($params);
    }

    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive($context = null)
    {
        if ($this->group !== null) {
            return $this->name === $this->group->select($context);
        }

        return $this->process($context);
    }

    /**
     * @param GroupInterface $group
     * @return static
     */
    public function setGroup(GroupInterface $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    protected function isValidProcessedResult($result)
    {
        return is_bool($result);
    }
}
