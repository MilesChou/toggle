<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\FeatureTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;

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
     * @param callable|string|null $processor
     */
    public function __construct(array $features, $processor = null)
    {
        $this->features = $features;

        if (null === $processor) {
            return;
        }

        if (is_callable($processor)) {
            $this->setProcessor($processor);
            return;
        }

        if (is_string($processor)) {
            $this->setProcessedResult($processor);
            return;
        }

        throw new \InvalidArgumentException('The Group\'s processor must be callable or string result');
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
        foreach ($this->features as $name => $feature) {
            $toggle = $name === $featureName;

            $this->features[$name]->setProcessedResult($toggle);
        }
    }
}
