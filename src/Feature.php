<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Concerns\ResultAwareTrait;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;
use MilesChou\Toggle\Contracts\ResultInterface;
use MilesChou\Toggle\Processors\Processor;

class Feature implements FeatureInterface, ParameterAwareInterface, ResultInterface
{
    use ParameterAwareTrait;
    use ProcessorAwareTrait;
    use ResultAwareTrait;

    /**
     * @param callable|array|bool|null $processor
     * @param array $params
     * @return static
     */
    public static function create($processor = null, array $params = [])
    {
        // default is false
        if (null === $processor) {
            $processor = false;
        }

        if (is_bool($processor)) {
            $processor = function () use ($processor) {
                return $processor;
            };
        }

        if (is_array($processor)) {
            $processor = Processor::retrieve($processor);
        }

        return new static($processor, $params);
    }

    /**
     * @param callable $processor The callable will return bool
     * @param array $params
     */
    public function __construct($processor, array $params = [])
    {
        $this->setProcessor($processor);
        $this->setParams($params);
    }

    /**
     * @param Context|null $context
     * @return bool
     */
    public function isActive($context = null)
    {
        return $this->process($context, $this->getParams());
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
