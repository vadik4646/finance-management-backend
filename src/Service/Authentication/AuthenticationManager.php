<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Entity\User;
use App\Utils\RequestAttributeKey;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticationManager
{
  /**
   * @var TokenManager
   */
  private $tokenManager;

  /**
   * @var TokenStorage
   */
  private $tokenStorage;

  public function __construct(TokenManager $tokenManager, TokenStorage $tokenStorage)
  {
    $this->tokenManager = $tokenManager;
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * @param User    $user
   * @param Request $request
   * @return Token
   */
  public function authenticate(User $user, Request $request)
  {
    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    $this->tokenStorage->setToken($token);
    $authToken = $this->tokenManager->create($user);
    $request->attributes->set(RequestAttributeKey::TOKEN, $authToken);

    return $authToken;
  }

  /**
   * @param Request $request
   */
  public function logout(Request $request)
  {
    $token = $request->attributes->get(RequestAttributeKey::TOKEN);
    $this->tokenManager->delete($token);
    $request->attributes->set(RequestAttributeKey::TOKEN, null);
    $this->tokenStorage->setToken(null);
  }
}
