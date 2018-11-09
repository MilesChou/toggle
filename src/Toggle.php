<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Providers\DataProvider;
use MilesChou\Toggle\Providers\ResultProvider;
use RuntimeException;

class Toggle
{
    use ContextAwareTrait;
    use FeatureAwareTrait;

    /**
     * @var bool
     */
    private $preserve = true;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param Context|null $context
     * @param DataProvider|null $dataProvider
     * @return ProviderInterface
     */
    public function export(Context $context = null, DataProvider $dataProvider = null)
    {
        if (null === $dataProvider) {
            $dataProvider = new DataProvider();
        }

        $context = $this->resolveContext($context);

        return $dataProvider
            ->setFeatures($this->features, $context);
    }

    /**
     * @param DataProvider $dataProvider
     * @param bool $clean
     */
    public function import(DataProvider $dataProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeatures();
        }

        foreach ($dataProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['return'], $feature['params']);
        }
    }

    /**
     * @param string $name
     * @param null|Context $context
     * @return bool
     */
    public function isActive($name, Context $context = null)
    {
        if (!$this->hasFeature($name)) {
            if ($this->strict) {
                throw new RuntimeException("Feature '{$name}' is not found");
            }

            return false;
        }

        $feature = $this->feature($name);

        if ($feature->hasStaticResult()) {
            return $feature->staticResult();
        }

        if (isset($this->featuresPreserveResult[$name])) {
            return $this->featuresPreserveResult[$name];
        }

        $context = $this->resolveContext($context);
        $result = $feature->isActive($context);

        if ($this->preserve) {
            $this->featuresPreserveResult[$name] = $result;
        }

        return $result;
    }

    /**
     * Import / export result data
     *
     * @param ResultProvider|null $resultProvider
     * @return ResultProvider
     */
    public function result(ResultProvider $resultProvider = null)
    {
        if (null === $resultProvider) {
            return new ResultProvider([
                'feature' => $this->featuresPreserveResult,
            ]);
        }

        $this->featuresPreserveResult = array_merge($this->featuresPreserveResult, $resultProvider->getFeatures());

        return $resultProvider;
    }

    /**
     * @param bool $preserve
     * @return static
     */
    public function setPreserve($preserve)
    {
        $this->preserve = $preserve;

        return $this;
    }

    /**
     * @param bool $strict
     * @return static
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * When $feature on, then call $callable
     *
     * @param string $feature
     * @param callable $callable
     * @param Context|null $context
     *
     * @return static
     */
    public function when($feature, callable $callable, Context $context = null)
    {
        if ($this->isActive($feature, $context)) {
            $callable();
        }

        return $this;
    }
}
