<?php

namespace MilesChou\Toggle\Contracts;

interface ProviderInterface
{
    /**
     * @param array $data
     * @param array $context
     * @return static
     */
    public function fill(array $data, array $context = null);

    /**
     * @return array
     */
    public function toArray();
}
