<?php

namespace MilesChou\Toggle\Contracts;

interface ResultInterface
{
    /**
     * @param mixed|null $result
     * @return static|mixed|null
     */
    public function result($result = null);
}
