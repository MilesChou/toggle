<?php

namespace MilesChou\Toggle\Contracts;

interface SerializerInterface
{
    /**
     * @return static
     */
    public function serialize();

    /**
     * @param string $str
     * @return static
     */
    public function unserialize($str);
}
