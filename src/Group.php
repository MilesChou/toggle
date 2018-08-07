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

        $this->init($processor);
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
        if (!$this->isValidProcessedResult($result)) {
            throw new \RuntimeException('Processed result must be features list');
        }
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
