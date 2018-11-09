<?php

namespace MilesChou\Toggle\Concerns;

trait StaticResultAwareTrait
{
    /**
     * @var bool
     */
    private $staticResult;

    /**
     * @return void
     */
    public function cleanStaticResult()
    {
        $this->staticResult = null;
    }

    /**
     * @return bool
     */
    public function hasStaticResult()
    {
        return null !== $this->staticResult;
    }

    /**
     * @return bool|null
     */
    public function staticResult()
    {
        return $this->staticResult;
    }

    /**
     * @param bool|null $result
     * @return static
     */
    public function setStaticResult($result = null)
    {
        $this->staticResult = $result;

        return $this;
    }
}
