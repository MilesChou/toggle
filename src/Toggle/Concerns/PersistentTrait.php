<?php

namespace MilesChou\Toggle\Concerns;

use MilesChou\Toggle\Contracts\PersistenceInterface;

trait PersistentTrait
{
    /**
     * Save config into persistence object
     *
     * @param PersistenceInterface $persistence
     */
    public function save(PersistenceInterface $persistence)
    {
        $persistence->store($this->result());
    }

    /**
     * Load config from persistence object
     *
     * @param PersistenceInterface $persistence
     */
    public function load(PersistenceInterface $persistence)
    {
        $this->result($persistence->restore());
    }
}
