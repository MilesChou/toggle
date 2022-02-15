<?php

namespace MilesChou\Toggle\Persistence;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\Cookie;
use MilesChou\Toggle\Contracts\PersistenceInterface;
use MilesChou\Toggle\Serializers\JsonSerializer;

class LaravelCookie implements PersistenceInterface
{
    /**
     * @var bool
     */
    protected $encrypt = true;

    /**
     * @var bool
     */
    protected $key = '_f';

    /**
     * @var bool
     */
    protected $minutes = 43200;

    /**
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * @var Encrypter
     */
    private $encrypter;

    public function __construct(Encrypter $encrypter, CookieJar $cookieJar)
    {
        $this->encrypter = $encrypter;
        $this->cookieJar = $cookieJar;
    }

    public function restore(): array
    {
        if (!Cookie::has($this->key)) {
            return [];
        }

        $value = Cookie::get($this->key);

        if ($this->encrypt) {
            $value = $this->decryptCookieValue($value);
        }

        return (new JsonSerializer())->deserialize($value);
    }

    public function store(array $data)
    {
        $serialized = (new JsonSerializer())->serialize($data);

        $value = $this->encrypt
            ? $this->encrypter->encrypt($serialized)
            : $serialized;

        $time = time() + $this->minutes * 60;

        Cookie::queue($this->cookieJar->make($this->key, $value, $time, null, null, false, true));
    }

    /**
     * @param null|string $value
     * @return null|string
     */
    private function decryptCookieValue($value)
    {
        try {
            return $this->encrypter->decrypt($value);
        } catch (DecryptException $exception) {
            return '[]';
        }
    }
}
