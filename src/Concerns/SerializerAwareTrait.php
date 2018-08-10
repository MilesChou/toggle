<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Contracts\SerializerInterface;
use RuntimeException;

trait SerializerAwareTrait
{
    /**
     * @var SerializerInterface[]
     */
    private static $serializerInstances = [];

    /**
     * @var mixed
     */
    private $serializerDriver = 'MilesChou\\Toggle\\Serializers\\JsonSerializer';

    /**
     * @param mixed $serializerDriver
     * @return static
     */
    public function setSerializerDriver($serializerDriver)
    {
        if (!class_exists($serializerDriver)) {
            throw new InvalidArgumentException("Class '{$serializerDriver}' not found");
        }

        $this->serializerDriver = $serializerDriver;

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $data = $this->retrieveStoreData();

        return $this->resolveSerializer($this->serializerDriver)->serialize($data);
    }

    /**
     * @param string $str
     * @return static
     */
    public function unserialize($str)
    {
        $data = $this->resolveSerializer($this->serializerDriver)->unserialize($str);

        return $this->retrieveRestoreData($data);
    }

    /**
     * @param string $driver
     * @return SerializerInterface
     */
    protected function resolveSerializer($driver)
    {
        if (!array_key_exists($driver, self::$serializerInstances)) {
            self::$serializerInstances[$driver] = new $driver();

            if (!(self::$serializerInstances[$driver] instanceof SerializerInterface)) {
                throw new RuntimeException("Class '{$driver}' must be implement SerializerInterface");
            }
        }

        return self::$serializerInstances[$driver];
    }

    /**
     * @param mixed $data
     * @return static
     */
    abstract protected function retrieveRestoreData($data);

    /**
     * @return mixed
     */
    abstract protected function retrieveStoreData();
}
