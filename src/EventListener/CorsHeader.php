<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsHeader
{
  /**
   * @param FilterResponseEvent $event
   */
  public function onKernelResponse(FilterResponseEvent $event)
  {
    //    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    //      $response = new Response();
    $event->getResponse()->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept, X-AUTH-TOKEN');
    $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
    $event->getResponse()->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
  }
}
