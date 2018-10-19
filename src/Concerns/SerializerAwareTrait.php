<?php

namespace MilesChou\Toggle\Concerns;

use InvalidArgumentException;
use MilesChou\Toggle\Contracts\SerializerInterface;
use RuntimeException;
use MilesChou\Toggle\Serializers\JsonSerializer;

trait SerializerAwareTrait
{
    /**
     * @var SerializerInterface[]
     */
    private static $serializerInstances = [];

    /**
     * @var mixed
     */
    private $serializerDriver = JsonSerializer::class;

    /**
     * @param string $str
     * @return static
     */
    public function deserialize($str)
    {
        $data = $this->resolveSerializer($this->serializerDriver)->deserialize($str);

        return $this->fill($data);
    }

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
        return $this->resolveSerializer($this->serializerDriver)->serialize($this->toArray());
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
}
