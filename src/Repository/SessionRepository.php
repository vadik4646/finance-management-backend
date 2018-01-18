<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SessionRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Session::class);
  }
}
