<?php

namespace App\EventListener;

use App\Entity\Token;
use App\Service\Authentication\TokenManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class TokenRegeneration
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function onKernelResponse(FilterResponseEvent $event)
  {
    /** @var Token $token */
    $token = $event->getRequest()->attributes->get('token');
    if (empty($token)) {
      $this->updateHeaders($event->getResponse());
      return;
    }

    $token->updateLastAction();

    if ($token->getLastActionAt()->getTimestamp() - $token->getCreatedAt()->getTimestamp() > 60) {
      $token->setId(TokenManager::generateId());
      $token->setCreatedAt(new DateTime('now'));
    }

    $this->entityManager->persist($token);
    $this->entityManager->flush();

    $this->updateHeaders($event->getResponse(), $token);
  }

  private function updateHeaders(Response $response, Token $token = null)
  {
    $tokenId = $token ? $token->getId() : null;
    $response->headers->add([TokenManager::TOKEN_KEY => $tokenId]);
    $response->headers->setCookie(new Cookie(TokenManager::TOKEN_KEY, $tokenId));
  }
}
