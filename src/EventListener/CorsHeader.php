<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CorsHeader
{
  /**
   * @param GetResponseEvent $event
   */
  public function onKernelRequest(GetResponseEvent $event)
  {
    if ($event->getRequest()->getRealMethod() === "OPTIONS") {
      $response = new Response();
      $event->setResponse($response);
      $event->stopPropagation();
    }
  }

  /**
   * @param FilterResponseEvent $event
   */
  public function onKernelResponse(FilterResponseEvent $event)
  {
    if (!$event->isMasterRequest() || $event->getRequest()->getRealMethod() !== "OPTIONS") {
      return;
    }

    $response = $event->getResponse();
    $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept, x-auth-token');
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
    $event->stopPropagation();
  }
}
