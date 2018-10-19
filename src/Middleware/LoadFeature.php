<?php

namespace MilesChou\Toggle\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\Encrypter;
use MilesChou\Toggle\DataProvider;
use MilesChou\Toggle\Toggle;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadFeature
{
    /**
     * Cookie key
     *
     * @var string
     */
    protected $key = '_f';

    /**
     * Cookie key
     *
     * @var int
     */
    protected $minutes = 43200;

    /**
     * @var bool
     */
    protected $encrypt = false;

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
     * @param Encrypter $encrypter
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
        if ($request->cookies->has($this->key)) {
            $value = $request->cookies->get($this->key);

            if ($this->encrypt) {
                $value = $this->encrypter->decrypt($value);
            }

            $dataProvider = new DataProvider();
            $dataProvider->deserialize($value);

            $this->toggle->import($dataProvider);
        }

        /** @var Response $response */
        $response = $next($request);

        /** @var DataProvider $data */
        $data = $this->toggle->export();

        $value = $this->encrypt
            ? $this->encrypter->encrypt($data->serialize())
            : $data->serialize();

        $time = time() + $this->minutes * 60;

        $response->headers->setCookie(
            new Cookie($this->key, $value, $time)
        );

        return $response;
    }
}
