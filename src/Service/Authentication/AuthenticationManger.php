<?php

namespace App\Service\Authentication;

use App\Entity\User;
use App\Service\Session\Session;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class AuthenticationManger
{
  /** @var Session */
  private $session;

  /** @var TokenStorageInterface */
  private $tokenStorage;

  /** @var EventDispatcherInterface */
  private $eventDispatcher;

  public function __construct(TokenStorageInterface $tokenStorage, Session $session, EventDispatcherInterface $eventDispatcher)
  {
    $this->tokenStorage = $tokenStorage;
    $this->session = $session;
    $this->eventDispatcher = $eventDispatcher;
  }

  public function authenticate(User $user)
  {
    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
    $this->tokenStorage->setToken($token);

    $this->session->migrate();
    $this->session->authenticate($user);
    $this->eventDispatcher->dispatch(AuthenticationEvents::AUTHENTICATION_SUCCESS, new AuthenticationEvent($token));
  }
}
