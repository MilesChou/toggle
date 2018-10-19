<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Concerns\GroupAwareTrait;
use MilesChou\Toggle\Contracts\ProviderInterface;
use MilesChou\Toggle\Providers\ArrayProvider;
use RuntimeException;

class Toggle
{
    use ContextAwareTrait;
    use FeatureAwareTrait;
    use GroupAwareTrait;

    /**
     * @var bool
     */
    private $preserve = true;

    /**
     * @var bool
     */
    private $strict = false;

    /**
     * @param string $dataProviderDriver
     * @param Context|null $context
     * @return ProviderInterface
     */
    public function export(Context $context = null, $dataProviderDriver = ArrayProvider::class)
    {
        if (!class_exists($dataProviderDriver)) {
            throw new RuntimeException("Unknown class {$dataProviderDriver}");
        }

        /** @var ProviderInterface $dataProvider */
        $dataProvider = new $dataProviderDriver();

        if (!$dataProvider instanceof ProviderInterface) {
            throw new RuntimeException('Driver must instance of Provider');
        }

        $context = $this->resolveContext($context);

        return $dataProvider
            ->setFeatures($this->features, $context)
            ->setGroups($this->groups, $context);
    }

    /**
     * @param ProviderInterface $dataProvider
     * @param bool $clean
     */
    public function import(ProviderInterface $dataProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeatures();
            $this->cleanGroup();
        }

        foreach ($dataProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['return'], $feature['params']);
        }

        foreach ($dataProvider->getGroups() as $name => $group) {
            $this->createGroup($name, $group['list'], $group['return'], $group['params']);
        }
    }

    /**
     * @param string $name
     * @param null|Context $context
     * @return bool
     */
    public function isActive($name, Context $context = null)
    {
        if (isset($this->featuresPreserveResult[$name])) {
            return $this->featuresPreserveResult[$name];
        }

        if (!array_key_exists($name, $this->features)) {
            if ($this->strict) {
                throw new RuntimeException("Feature '{$name}' is not found");
            }

            return false;
        }

        $context = $this->resolveContext($context);

        $result = $this->feature($name)->isActive($context);

        if ($this->preserve) {
            $this->featuresPreserveResult[$name] = $result;
        }

        return $result;
    }

    /**
     * @param string $name
     * @param null|Context $context
     * @return string
     */
    public function select($name, Context $context = null)
    {
        if (isset($this->groupsPreserveResult[$name])) {
            return $this->groupsPreserveResult[$name];
        }

        if (!array_key_exists($name, $this->groups)) {
            if ($this->strict) {
                throw new RuntimeException("Group '{$name}' is not found");
            }

            return null;
        }

        $context = $this->resolveContext($context);

        $result = $this->group($name)->select($context);

        if ($this->preserve) {
            $this->groupsPreserveResult[$name] = $result;
        }

        return $result;
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

    /**
     * @param bool $strict
     * @return static
     */
    public function setStrict($strict)
    {
        $this->strict = $strict;

        return $this;
    }
}
