<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Category::class);
  }

  /**
   * @param string $search
   * @param User   $user
   * @return Category[]
   */
  public function get($search, User $user = null) // todo elasticsearch or shpinx
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.user IS NULL OR c.user = :user')
      ->andWhere('c.name LIKE :search')
      ->setParameter('user', $user)
      ->setParameter('search', '%' . $search . '%')
      ->getQuery()
      ->getResult();
  }
}
