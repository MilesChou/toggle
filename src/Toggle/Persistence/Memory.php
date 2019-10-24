<?php

namespace MilesChou\Toggle\Persistence;

use MilesChou\Toggle\Contracts\PersistenceInterface;

class Memory implements PersistenceInterface
{
    /**
     * @var array
     */
    private $data = [];

    public function restore()
    {
        return $this->data;
    }

    public function store(array $data)
    {
        $this->data = $data;
    }
}
