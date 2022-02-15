<?php

namespace MilesChou\Toggle\Contracts;

interface ProcessorInterface
{
    /**
     * @param mixed $config
     */
    public function setConfig($config);

    /**
     * @return array
     */
    public function toArray(): array;
}
