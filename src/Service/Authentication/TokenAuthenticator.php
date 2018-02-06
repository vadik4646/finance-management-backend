<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Service\ApiResponse;
use App\Utils\RequestAttributeKey;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
  private $tokenManager;

  private $publicRoutes = ['register', 'login', 'tags', 'categories', 'welcome', 'append_error'];

  private $environment;

  public function __construct(TokenManager $tokenManager, $environment)
  {
    $this->tokenManager = $tokenManager;
    $this->environment = $environment;
  }

  /**
   * Called on every request to decide if this authenticator should be
   * used for the request. Returning false will cause this authenticator
   * to be skipped.
   */
  public function supports(Request $request)
  {
    return true;
  }

  /**
   * Called on every request. Return whatever credentials you want to
   * be passed to getUser() as $credentials.
   */
  public function getCredentials(Request $request)
  {
    $token = $this->tokenManager->getFromRequest($request);
    $request->attributes->set(RequestAttributeKey::TOKEN, $token);

    return [
      'token' => $token
    ];
  }

  /**
   * @param array                 $credentials
   * @param UserProviderInterface $userProvider
   * @return null|UserInterface
   */
  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    /** @var Token $token */
    $token = $credentials['token'];

    if (null === $token) {
      return null;
    }

    return $token->getUser();
  }

  public function checkCredentials($credentials, UserInterface $user)
  {
    return true;
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  {
    if (in_array($request->attributes->get('_route'), $this->publicRoutes)) {
      return null;
    }

    return $this->unauthorizedResponse();
  }

  /**
   * Called when authentication is needed, but it's not sent
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    return $this->unauthorizedResponse();
  }

  public function supportsRememberMe()
  {
    return false;
  }

  private function unauthorizedResponse()
  {
    return (new ApiResponse())
      ->setCode(ApiResponse::HTTP_UNAUTHORIZED)
      ->setMessage('You should be logged in to perform this action')
      ->get();
  }
}
