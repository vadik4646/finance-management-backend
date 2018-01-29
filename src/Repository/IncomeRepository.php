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

  public function searchByUser(User $user, $search)
  {
    $qb = $this->createQueryBuilder('i')
      ->select(['i', 'cur', 'cat', 'tag'])
      ->leftJoin('i.currency', 'cur')
      ->leftJoin('i.category', 'cat')
      ->leftJoin('i.tags', 'tag')
      ->where('i.user = :user')
      ->setParameter('user', $user)
      ->orderBy('i.id', 'DESC');

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
