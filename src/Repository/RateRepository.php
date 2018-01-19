<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Rate;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RateRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Rate::class);
  }

  public function get(Currency $currency, DateTime $date)
  {
    return $this->createQueryBuilder('r')
      ->where('r.currency = :currency')
      ->andWhere('r.date = :date')
      ->setParameter('currency', $currency)
      ->setParameter('date', $date->setTime(0, 0, 0))
      ->getQuery()
      ->getOneOrNullResult();
  }
}
