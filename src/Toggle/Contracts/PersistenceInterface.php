<?php

namespace MilesChou\Toggle\Contracts;

interface PersistenceInterface
{
    /**
     * Restore config
     *
     * @return array
     */
    public function restore();

    /**
     * Store config
     *
     * @param array $data
     */
    public function store(array $data);
}
