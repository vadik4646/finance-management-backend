<?php

namespace App\EventListener;

use App\Service\Log\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class DebugTokenLogger
{
  private $environment;

  /**
   * @var Logger
   */
  private $logger;

  public function __construct(Logger $logger, $environment)
  {
    $this->environment = $environment;
    $this->logger = $logger;
  }

  /**
   * @param PostResponseEvent $event
   */
  public function onKernelTerminate(PostResponseEvent $event)
  {
    $response = $event->getResponse();
    if ($this->environment === 'dev' && $response->getStatusCode() === Response::HTTP_INTERNAL_SERVER_ERROR) {
      $debugLink = $event->getResponse()->headers->get('X-Debug-Token-Link');
      $this->logger->addDebugLink($debugLink);
    }
  }
}
