<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Context;
use MilesChou\Toggle\Contracts\ProviderInterface;

abstract class Provider implements ProviderInterface
{
    /**
     * @var array
     */
    protected $features = [];

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data = [])
    {
        return new static($data);
    }

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * @param array $data
     * @param Context|null $context
     * @return static
     */
    public function fill(array $data, $context = null)
    {
        if (isset($data['feature'])) {
            $this->setFeatures($data['feature'], $context);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'feature' => $this->getFeatures(),
        ];
    }
}
