<?php
namespace App\EventListener;

use App\Service\Authentication\TokenProvider;
use App\Service\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class SessionSaver
{
  private $session;

  public function __construct(Session $session)
  {
    $this->session = $session;
  }

  /**
   * @param FilterResponseEvent $event
   */
  public function onKernelResponse(FilterResponseEvent $event)
  {
    $response = $event->getResponse();

    $this->session->start();
    $sessionId = $this->session->getId();

    $this->session->save();

    $response->headers->setCookie(new Cookie(TokenProvider::TOKEN_KEY, $sessionId));
    $response->headers->set(TokenProvider::TOKEN_KEY, $sessionId);
  }
}
