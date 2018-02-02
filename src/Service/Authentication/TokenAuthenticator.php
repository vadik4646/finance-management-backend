<?php

namespace App\Service\Authentication;

use App\Service\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
  private $tokenProvider;

  private $publicRoutes = ['register', 'login', 'tags', 'categories', 'welcome', 'append_error'];

  private $environment;

  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(TokenProvider $tokenProvider, EntityManagerInterface $entityManager, $environment)
  {
    $this->tokenProvider = $tokenProvider;
    $this->environment = $environment;
    $this->entityManager = $entityManager;
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
    return [
      'token' => $this->tokenProvider->getFromRequest($request)
    ];
  }

  /**
   * @param array                 $credentials
   * @param UserProviderInterface $userProvider
   * @return null|UserInterface
   */
  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    $token = $credentials['token'];

    if (null === $token) {
      return null;
    }

    if ($this->environment === 'dev' && $token === 'dev') {
      return $userProvider->loadUserByUsername('dev@dev.com');
    }

    return $userProvider->loadUserByUsername($token);
  }

  public function checkCredentials($credentials, UserInterface $user)
  {
    return true;
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    // todo regenerate if updated at > 1hour
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  {
    if (in_array($request->attributes->get('_route'), $this->publicRoutes)) {
      return null;
    }

    $response = new ApiResponse();

    return $response
      ->setCode(ApiResponse::HTTP_UNAUTHORIZED)
      ->setMessage("You should be logged in to perform this action")
      ->get();
  }

  /**
   * Called when authentication is needed, but it's not sent
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    $data = [
      'message' => 'Authentication Required'
    ];

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }

  public function supportsRememberMe()
  {
    return false;
  }
}
