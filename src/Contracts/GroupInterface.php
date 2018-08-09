<?php

namespace MilesChou\Toggle\Contracts;

use MilesChou\Toggle\Context;

interface GroupInterface
{
    /**
     * @param Context|null $context
     * @return string
     */
    public function select($context = null);
}
