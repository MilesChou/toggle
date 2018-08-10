<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Concerns\NameAwareTrait;
use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Contracts\GroupInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Group implements GroupInterface, ParameterAwareInterface
{
    use FeatureAwareTrait;
    use NameAwareTrait;
    use ParameterAwareTrait;
    use ProcessorAwareTrait;

    /**
     * @param string $name
     * @param Feature[] $features
     * @param callable|string|null $processor The callable will return bool
     * @param array $params
     * @return static
     */
    public static function create($name, array $features, $processor = null, array $params = [])
    {
        if (is_string($processor)) {
            $result = $processor;

            $processor = function () use ($result) {
                return $result;
            };
        }

        return new static($name, $features, $processor, $params);
    }

    /**
     * @param string $name
     * @param Feature[] $features
     * @param callable|string|null $processor
     * @param array $params
     */
    public function __construct($name, array $features, $processor = null, array $params = [])
    {
        $this->name = $name;
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
