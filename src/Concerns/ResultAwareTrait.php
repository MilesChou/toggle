<?php

namespace MilesChou\Toggle\Concerns;

trait ResultAwareTrait
{
    /**
     * @var bool
     */
    private $result;

    /**
     * @return void
     */
    public function cleanResult()
    {
        $this->result = null;
    }

    /**
     * @return bool
     */
    public function hasResult()
    {
        return null !== $this->result;
    }

    /**
     * @param bool|null $result
     * @return static|bool|null
     */
    public function result($result = null)
    {
        if (null === $result) {
            return $this->result;
        }

        $this->result = $result;

        return $this;
    }
}
