<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\Encrypter;
use MilesChou\Toggle\Providers\ResultProvider;
use MilesChou\Toggle\Serializers\JsonSerializer;
use MilesChou\Toggle\Toggle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadFeature
{
    /**
     * Cookie key
     *
     * @var string
     */
    protected $key = '_feat';

    /**
     * Cookie key
     *
     * @var int
     */
    protected $minutes = 43200;

    /**
     * @var bool
     */
    protected $encrypt = true;

    /**
     * @var Toggle
     */
    private $toggle;

    /**
     * @var Encrypter
     */
    private $encrypter;

    /**
     * @param Toggle $toggle
     */
    public function __construct(Toggle $toggle, Encrypter $encrypter)
    {
        $this->toggle = $toggle;
        $this->encrypter = $encrypter;
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
                $value = $this->encrypter->decrypt($value);
            }

            $this->toggle->result($jsonSerializer->deserialize($value, new ResultProvider()));
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
            cookie($this->key, $value, $time)
        );

        return $response;
    }
}
