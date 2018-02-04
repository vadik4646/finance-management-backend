<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TokenProvider
{
  private $requestStack;

  const TOKEN_KEY = 'X-AUTH-TOKEN';

  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
  {
    $this->requestStack = $requestStack;
    $this->entityManager = $entityManager;
  }

  /**
   * @param Request $request
   * @return string
   */
  public function getFromRequest(Request $request)
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
  public function isInRequest(Request $request)
  {
    return $request->headers->has(self::TOKEN_KEY) || $request->cookies->has(self::TOKEN_KEY);
  }

  /**
   * @param User        $user
   * @param string|null $tokenStr
   * @param string|null $country
   * @param string|null $device
   * @return Token
   * @throws \Exception
   */
  public function create(User $user, $tokenStr = null, $country = null, $device = null)
  {
    $token = new Token();
    $token->setId($tokenStr ?: hash('sha256', random_bytes(64)));
    $token->setUser($user);
    $token->setCountry($country ?: 'MD');
    $token->setDevice($device ?: 'Calculator');

    $this->entityManager->persist($token);
    $this->entityManager->flush();

    return $token;
  }
}
