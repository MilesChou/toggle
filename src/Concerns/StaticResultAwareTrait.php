<?php

namespace MilesChou\Toggle\Concerns;

trait StaticResultAwareTrait
{
    /**
     * @var bool
     */
    private $isStaticResult = false;

    /**
     * @var mixed
     */
    private $staticResult;

    public function cleanStaticResult()
    {
        $this->isStaticResult = false;
        $this->staticResult = null;
    }

    /**
     * @return bool
     */
    public function hasStaticResult()
    {
        return $this->isStaticResult;
    }

    /**
     * @param mixed|null $result
     * @return static|mixed
     */
    public function staticResult($result = null)
    {
        if (null === $result) {
            return $this->staticResult;
        }

        $this->isStaticResult = true;
        $this->staticResult = $result;

        return $this;
    }
}
