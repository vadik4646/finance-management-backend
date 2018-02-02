<?php

namespace App\Service\Authentication;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserByTokenProvider implements UserProviderInterface
{
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  /**
   * Loads the user for the given username.
   *
   * This method must throw UsernameNotFoundException if the user is not
   * found.
   *
   * @param string $token The username
   *
   * @return UserInterface
   *
   * @throws UsernameNotFoundException if the user is not found
   */
  public function loadUserByUsername($token)
  {
    $token = $this->entityManager->getRepository(Token::class)->find($token);
    if (empty($token) || !$user = $token->getUser()) {
      throw new UsernameNotFoundException();
    }

    return $user;
  }

  /**
   * Refreshes the user.
   *
   * It is up to the implementation to decide if the user data should be
   * totally reloaded (e.g. from the database), or if the UserInterface
   * object can just be merged into some internal array of users / identity
   * map.
   *
   * @param UserInterface $user
   * @return UserInterface
   *
   * @throws UnsupportedUserException if the user is not supported
   */
  public function refreshUser(UserInterface $user)
  {
    if (!$user instanceof User) {
      throw new UnsupportedUserException(
        sprintf('Instances of "%s" are not supported.', get_class($user))
      );
    }

    return $this->loadUserByUsername($user->getUsername());
  }

  /**
   * Whether this provider supports the given user class.
   *
   * @param string $class
   *
   * @return bool
   */
  public function supportsClass($class)
  {
    return User::class === $class;
  }
}
