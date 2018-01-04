<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, User::class);
  }

  /**
   * @param string $email
   * @return User
   */
  public function loadUserByEmail($email)
  {
    return $this->createQueryBuilder('u')
      ->where('u.email = :email')
      ->setParameter('email', $email)
      ->getQuery()
      ->getSingleResult();
  }
}
