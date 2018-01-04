<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ExpenseRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Expense::class);
  }

  public function findByUser(User $user)
  {
    return $this->createQueryBuilder('e')
      ->select(['e', 'cur', 'cat', 'tag'])
      ->where('e.user = :user')
      ->leftJoin('e.currency', 'cur')
      ->leftJoin('e.category', 'cat')
      ->leftJoin('e.tags', 'tag')
      ->setParameter('user', $user)
      ->orderBy('e.id', 'DESC')
      ->getQuery()
      ->getResult();
  }
}
