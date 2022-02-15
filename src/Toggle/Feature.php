<?php

namespace MilesChou\Toggle;

use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Concerns\ProcessorAwareTrait;
use MilesChou\Toggle\Contracts\FeatureInterface;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;

class Feature implements FeatureInterface, ParameterAwareInterface
{
    use ParameterAwareTrait;
    use ProcessorAwareTrait;

    /**
     * @var bool
     */
    private $result;

    /**
     * @param callable|array|bool|null $processor
     * @param array $params
     * @param bool|null $staticResult
     * @return static
     */
    public static function create($processor = null, array $params = [], ?bool $staticResult = null): Feature
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
            $processor = Factory::retrieveProcessor($processor);
        }

        static::assertProcessor($processor);

        return new static($processor, $params, $staticResult);
    }

    /**
     * @param callable $processor The callable must return bool type
     * @param array $params
     * @param bool|null $result
     */
    public function __construct(callable $processor, array $params = [], ?bool $result = null)
    {
        $this->processor($processor)
            ->params($params)
            ->result($result);
    }

    public function disable(): FeatureInterface
    {
        $this->result(false);

        return $this;
    }

    public function enable(): FeatureInterface
    {
        $this->result(true);

        return $this;
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->result = null;
    }

    /**
     * @return bool
     */
    public function hasResult(): bool
    {
        return null !== $this->result;
    }

    /**
     * @param array $context
     * @return bool
     */
    public function isActive(array $context = []): bool
    {
        return $this->process($context, $this->getParams());
    }

    /**
     * @param bool|null $result
     * @return static|bool|null
     */
    public function result($result = null)
    {
        if (null === $result) {
            return $this->result;
        }

        $this->result = $result;

        return $this;
    }

    /**
     * @param mixed $result
     * @return bool
     */
    protected function isValidProcessedResult($result): bool
    {
        return is_bool($result);
    }
}
