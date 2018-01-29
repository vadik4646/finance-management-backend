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

  public function searchByUser(User $user, $search)
  {
    $qb = $this->createQueryBuilder('e')
      ->select(['e', 'cur', 'cat', 'tag'])
      ->leftJoin('e.currency', 'cur')
      ->leftJoin('e.category', 'cat')
      ->leftJoin('e.tags', 'tag')
      ->where('e.user = :user')
      ->setParameter('user', $user)
      ->orderBy('e.id', 'DESC');

    if ($search) {
      $qb->where('cat.name LIKE :search')
        ->where('tag.value LIKE :search')
        ->setParameter('search', '%' . $search . '%');
    }

    return $qb->getQuery()->getResult();
  }

  public function createSearchQueryBuilder($entityAlias)
  {
    return $this->createQueryBuilder($entityAlias)
      ->select([$entityAlias, 'cur', 'cat', 'tag'])
      ->leftJoin($entityAlias . '.currency', 'cur')
      ->leftJoin($entityAlias . '.category', 'cat')
      ->leftJoin($entityAlias . '.tags', 'tag');
  }
}
