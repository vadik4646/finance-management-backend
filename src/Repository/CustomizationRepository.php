<?php

namespace App\Repository;

use App\Entity\Customization;
use App\Entity\User;
use App\Utils\Type\CustomizationKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CustomizationRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Customization::class);
  }

  public function findByUser(User $user)
  {
    return $this->createQueryBuilder('c')
      ->where('c.user = :user')
      ->setParameter('user', $user)
      ->getQuery()
      ->getResult();
  }

  public function findOrCreate(User $user, $name)
  {
    if (!$customization = $this->findOneBy(['user' => $user, 'name' => $name])) {
      $customization = $this->create($user, $name);
    }

    return $customization;
  }

  public function create(User $user, $name)
  {
    $customization = new Customization();
    $customization->setUser($user);
    $customization->setName($name);

    return $customization;
  }

  public function getCurrencyCode(User $user)
  {
    return $this->findOneBy(['name' => CustomizationKey::CURRENCY, 'user' => $user]);
  }
}
