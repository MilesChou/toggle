<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ContextAwareTrait;
use MilesChou\Toggle\Concerns\FeatureAwareTrait;
use MilesChou\Toggle\Concerns\GroupAwareTrait;
use MilesChou\Toggle\Contracts\DataProviderInterface;
use RuntimeException;

class Manager
{
    use ContextAwareTrait;
    use FeatureAwareTrait;
    use GroupAwareTrait;

    /**
     * @var bool
     */
    private $preserve = true;

    /**
     * @param string $dataProviderDriver
     * @param Context|null $context
     * @return DataProviderInterface
     */
    public function export(Context $context = null, $dataProviderDriver = 'MilesChou\\Toggle\\DataProvider')
    {
        if (!class_exists($dataProviderDriver)) {
            throw new RuntimeException("Unknown class {$dataProviderDriver}");
        }

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = new $dataProviderDriver();

        if (!$dataProvider instanceof DataProviderInterface) {
            throw new RuntimeException('Driver must instance of Provider');
        }

        $context = $this->resolveContext($context);

        return $dataProvider
            ->setFeatures($this->features, $context)
            ->setGroups($this->groups, $context);
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @param bool $clean
     */
    public function import(DataProviderInterface $dataProvider, $clean = true)
    {
        if ($clean) {
            $this->cleanFeatures();
            $this->cleanGroup();
        }

        foreach ($dataProvider->getFeatures() as $name => $feature) {
            $this->createFeature($name, $feature['r'], $feature['p']);
        }

        foreach ($dataProvider->getGroups() as $name => $group) {
            $this->createGroup($name, $group['l'], $group['r'], $group['p']);
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
            throw new RuntimeException("Feature '{$name}' is not found");
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
            throw new RuntimeException("Group '{$name}' is not found");
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
}
