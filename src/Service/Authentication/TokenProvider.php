<?php

namespace App\Service\Authentication;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TokenProvider
{
  private $requestStack;

  const TOKEN_KEY = 'X-AUTH-TOKEN';

  public function __construct(RequestStack $requestStack)
  {
    $this->requestStack = $requestStack;
  }

  /**
   * @param Request $request
   * @return string
   */
  public function get(Request $request)
  {
    $token = $request->headers->get(self::TOKEN_KEY);
    if (!$token) {
      $token = $request->cookies->get(self::TOKEN_KEY);
    }

    return $token;
  }

  /**
   * @param Request $request
   * @return bool
   */
  public function has(Request $request)
  {
    return $request->headers->has(self::TOKEN_KEY) || $request->cookies->has(self::TOKEN_KEY);
  }
}
