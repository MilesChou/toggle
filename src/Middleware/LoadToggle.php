<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Cookie\CookieJar;
use MilesChou\Toggle\Providers\ResultProvider;
use MilesChou\Toggle\Serializers\JsonSerializer;
use MilesChou\Toggle\Toggle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadToggle
{
    /**
     * @var bool
     */
    protected $encrypt = true;

    /**
     * Cookie key
     *
     * @var string
     */
    protected $key = '_f';

    /**
     * @var int
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

    /**
     * @var Toggle
     */
    private $toggle;

    /**
     * @param Toggle $toggle
     */
    public function __construct(Toggle $toggle, Encrypter $encrypter, CookieJar $cookieJar)
    {
        $this->toggle = $toggle;
        $this->encrypter = $encrypter;
        $this->cookieJar = $cookieJar;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $jsonSerializer = new JsonSerializer();

        if ($request->cookies->has($this->key)) {
            $value = $request->cookies->get($this->key);

            if ($this->encrypt) {
                $value = $this->decryptCookieValue($value);
            }

            $data = $jsonSerializer->deserialize($value, new ResultProvider());

            $this->toggle->result($data->toArray());
        }

        /** @var Response $response */
        $response = $next($request);

        $result = $this->toggle->result();
        $serialized = $jsonSerializer->serialize($result);

        $value = $this->encrypt
            ? $this->encrypter->encrypt($serialized)
            : $serialized;

        $time = time() + $this->minutes * 60;

        $response->headers->setCookie(
            $this->cookieJar->make($this->key, $value, $time)
        );

        return $response;
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
