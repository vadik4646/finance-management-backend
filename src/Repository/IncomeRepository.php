<?php

namespace App\Repository;

use App\Entity\Income;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IncomeRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Income::class);
  }

  public function findByUser(User $user)
  {
    return $this->createQueryBuilder('i')
      ->select(['i', 'cur', 'cat', 'tag'])
      ->where('i.user = :user')
      ->leftJoin('i.currency', 'cur')
      ->leftJoin('i.category', 'cat')
      ->leftJoin('i.tags', 'tag')
      ->setParameter('user', $user)
      ->orderBy('i.id', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
