<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
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
   * @ORM\Column(type="string")
   * @Assert\NotBlank()
   * @Fetcher()
   */
  private $value;

  /**
   * @var Expense[]
   *
   * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="tags")
   */
  private $expenses;

  /**
   * @var User|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
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
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @param string $value
   */
  public function setValue($value)
  {
    $this->value = $value;
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

  /**
   * @return Expense[]
   */
  public function getExpenses()
  {
    return $this->expenses;
  }

  /**
   * @param Expense[] $expenses
   */
  public function setExpenses($expenses)
  {
    $this->expenses = $expenses;
  }

}
