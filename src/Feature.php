<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\NameAwareTrait;
use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Feature implements FeatureInterface, ParameterAwareInterface
{
    use ParameterAwareTrait;
    use ProcessorAwareTrait;
    use NameAwareTrait;

    /**
     * @param string $name
     * @param callable|bool|null $processor
     * @param array $params
     * @return static
     */
    public static function create($name, $processor = null, array $params = [])
    {
        if (is_bool($processor)) {
            $result = $processor;

            $processor = function () use ($result) {
                return $result;
            };
        }

        return new static($name, $processor, $params);
    }

    /**
     * @param string $name
     * @param callable|bool|null $processor The callable will return bool
     * @param array $params
     */
    public function __construct($name, $processor = null, array $params = [])
    {
        $this->name = $name;
        $this->init($processor);
        $this->params = $params;
    }

    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive($context = null)
    {
        return $this->process($context);
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
