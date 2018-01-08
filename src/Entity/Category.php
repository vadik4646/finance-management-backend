<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Fetcher()
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(type="string", length=250)
   * @Assert\NotBlank()
   * @Fetcher()
   */
  private $name;

  /**
   * @var User|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(nullable=true)
   */
  private $user = null;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return User|null
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User|null $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }
}
