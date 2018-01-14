<?php
namespace App\EventListener;

use App\Service\Session\Session;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SessionInitializer
{
  private $session;

  public function __construct(Session $session)
  {
    $this->session = $session;
  }

  /**
   * @param GetResponseEvent $event
   */
  public function onKernelRequest(GetResponseEvent $event)
  {
    $this->handleSession($event->getRequest());
  }

  /**
   * @param Request $request
   */
  private function handleSession(Request $request)
  {
    $this->session->start();

    $now = new DateTime();
    $secondsDifference = $now->getTimestamp() - $this->session->getMetadataBag()->createdAt->getTimestamp();
    if ($secondsDifference > 60 * 60 * 4) {
      $this->session->migrate();
    }
    $request->setSession($this->session);
  }
}
