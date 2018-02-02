<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Entity\User;
use App\Service\ApiResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationManager
{
  /**
   * @var TokenStorage
   */
  private $tokenStorage;

  /**
   * @var TokenProvider
   */
  private $tokenProvider;

  public function __construct(TokenStorage $tokenStorage, TokenProvider $tokenProvider)
  {
    $this->tokenStorage = $tokenStorage;
    $this->tokenProvider = $tokenProvider;
  }

  /**
   * @param User        $user
   * @param ApiResponse $response
   * @return Token
   */
  public function authenticate(User $user, ApiResponse $response)
  {
    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    $this->tokenStorage->setToken($token);
    $authToken = $this->tokenProvider->create($user);
    $response->setAuthenticationToken($authToken->getId());

    return $authToken;
  }
}
