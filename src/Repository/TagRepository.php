<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TagRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Tag::class);
  }

  /**
   * @param array     $tags
   * @param User|null $user
   * @return Tag[]
   */
  public function createOrGetExisting(array $tags, User $user = null)
  {
    /** @var Tag[] $dbTags */
    $dbTags = $this->createQueryBuilder('t')
      ->where('t.value in (:tags)')
      ->andWhere('t.user IS NULL OR t.user = :user')
      ->setParameter('tags', $tags)
      ->setParameter('user', $user)
      ->getQuery()
      ->getResult();

    $existingTags = [];
    foreach ($dbTags as $dbTag) {
      $existingTags[] = $dbTag;
      unset($tags[array_search($dbTag->getValue(), $tags)]);
    }

    return array_merge($existingTags, $this->createMultiple($tags, $user));
  }

  /**
   * @param string[]  $tags
   * @param User|null $user
   * @return Tag[]
   */
  public function createMultiple(array $tags, User $user = null)
  {
    $newTags = [];
    foreach ($tags as $tag) {
      $newTag = new Tag();
      $newTag->setUser($user);
      $newTag->setValue($tag);
      $newTags[] = $newTag;
      $this->getEntityManager()->persist($newTag);
    }

    $this->getEntityManager()->flush();

    return $newTags;
  }
}
