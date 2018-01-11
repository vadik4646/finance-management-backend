<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CorsHeader
{
  /**
   * @param GetResponseEvent $event
   */
  public function onKernelRequest(GetResponseEvent $event)
  {
    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
      $response = new Response();
      $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept, X-AUTH-TOKEN');
      $response->headers->set('Access-Control-Allow-Origin', '*');
      $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

      $event->setResponse($response);
    }
  }
}