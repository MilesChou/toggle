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
     * @param callable|null $processor The callable will return bool
     * @param array $params
     * @return static
     */
    public static function create(array $features, $processor = null, array $params = [])
    {
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
        $feature = $this->process($context);

        $this->processFeaturesToggle($feature);

        return $feature;
    }

    /**
     * @param Context|null $context
     * @return Feature
     */
    public function selectFeatureInstance($context = null)
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

    /**
     * @param string $featureName
     */
    private function processFeaturesToggle($featureName)
    {
        foreach ($this->features as $name => $feature) {
            $toggle = $name === $featureName;

            $this->features[$name]->setProcessedResult($toggle);
        }
    }
}
