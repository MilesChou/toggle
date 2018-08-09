<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Contracts\GroupInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Group implements GroupInterface, ParameterAwareInterface
{
    use FeatureAwareTrait;
    use ParameterAwareTrait;
    use ProcessorAwareTrait;

    /**
     * @param Feature[] $features
     * @param callable|string|null $processor The callable will return bool
     * @param array $params
     * @return static
     */
    public static function create(array $features, $processor = null, array $params = [])
    {
        if (is_string($processor)) {
            $result = $processor;

            $processor = function () use ($result) {
                return $result;
            };
        }

        return new static($features, $processor, $params);
    }

    /**
     * @param Feature[] $features
     * @param callable|string|null $processor
     * @param array $params
     */
    public function __construct(array $features, $processor = null, array $params = [])
    {
        $this->features = $features;

        $this->init($processor);

        $this->params = $params;
    }

    /**
     * @param Context|null $context
     * @return string
     */
    public function select($context = null)
    {
        return $this->process($context);
    }

    /**
     * @param Context|null $context
     * @return Feature
     */
    public function selectFeature($context = null)
    {
        return $this->getFeature($this->select($context));
    }

    /**
     * @param mixed $result
     * @return bool
     */
    protected function isValidProcessedResult($result)
    {
        return is_string($result) && array_key_exists($result, $this->features);
    }
}
