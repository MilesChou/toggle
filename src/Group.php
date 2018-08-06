<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\FeatureTrait;
use MilesChou\Toggle\Traits\ProcessorAwareTrait;

class Group
{
    use FeatureTrait;
    use ProcessorAwareTrait;

    /**
     * @param Feature[] $features
     * @param callable|null $processor The callable will return bool
     * @return static
     */
    public static function create(array $features, $processor = null)
    {
        return new static($features, $processor);
    }

    /**
     * @param Feature[] $features
     * @param callable|null $processor
     */
    public function __construct(array $features, $processor = null)
    {
        $this->features = $features;
        $this->processor = $processor;
    }

    /**
     * @param null|Context $context
     * @return string
     */
    public function select(Context $context = null)
    {
        $feature = $this->process($context);

        $this->processFeaturesToggle($feature);

        return $feature;
    }

    protected function assertResult($result)
    {
        $featureNames = array_keys($this->features);

        if (!in_array($result, $featureNames, true)) {
            throw new \RuntimeException('Processed result must be features list');
        }
    }

    /**
     * @param string $featureName
     */
    private function processFeaturesToggle($featureName)
    {
        array_map(function ($name) use ($featureName) {
            $toggle = $name === $featureName;

            $this->features[$name]->setProcessedResult($toggle);
        }, array_keys($this->features));
    }
}
