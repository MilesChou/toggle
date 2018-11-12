<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Context;

interface ProviderInterface
{
    /**
     * @param array $data
     * @param Context|null $context
     * @return static
     */
    public function fill(array $data, $context = null);

    /**
     * @return array
     */
    public function toArray();
}
