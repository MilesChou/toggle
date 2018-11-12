<?php

namespace MilesChou\Toggle\Providers;

use MilesChou\Toggle\Concerns\ParameterAwareTrait;
use MilesChou\Toggle\Contracts\ParameterAwareInterface;
use MilesChou\Toggle\Contracts\ProviderInterface;

abstract class Provider implements ProviderInterface, ParameterAwareInterface
{
    use ParameterAwareTrait;

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
     * @return array
     */
    public function toArray()
    {
        return $this->getParams();
    }
}
