<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TokenManager
{
  const TOKEN_KEY = 'X-AUTH-TOKEN';
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  /**
   * @param Request $request
   * @return Token|null
   */
  public function getFromRequest(Request $request)
  {
    $tokenHash = $request->headers->get(self::TOKEN_KEY);
    if (!$tokenHash) {
      $tokenHash = $request->cookies->get(self::TOKEN_KEY);
    }

    return $tokenHash ? $this->entityManager->getRepository(Token::class)->find($tokenHash) : null;
  }

  /**
   * @param User        $user
   * @param string|null $tokenHash
   * @param string|null $country
   * @param string|null $device
   * @return Token
   */
  public function create(User $user, $tokenHash = null, $country = null, $device = null)
  {
    $token = new Token();
    $token->setId($tokenHash ?: self::generateId());
    $token->setUser($user);
    $token->setCountry($country ?: 'MD');
    $token->setDevice($device ?: 'Calculator');

    $this->entityManager->persist($token);
    $this->entityManager->flush();

    return $token;
  }

  /**
   * @param Token $token
   */
  public function delete(Token $token)
  {
    $this->entityManager->remove($token);
    $this->entityManager->flush();
  }

  /**
   * @return string
   */
  public static function generateId()
  {
    return hash('sha256', random_bytes(64));
  }
}
