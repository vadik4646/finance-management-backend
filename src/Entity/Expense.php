<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenseRepository")
 */
class Expense
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @var int
   *
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Fetcher()
   */
  private $id;

  /**
   * @var User
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $user;

  /**
   * @var float
   *
   * @ORM\Column(type="float", precision=2)
   * @Fetcher()
   */
  private $value;

  /**
   * @var Currency
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
   * @ORM\JoinColumn(referencedColumnName="code", name="currency_code")
   * @Fetcher()
   */
  private $currency;

  /**
   * @var Category
   *
   * @ORM\ManyToOne(targetEntity="Category")
   * @Fetcher()
   */
  private $category;

  /**
   * @var Tag[]
   *
   * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
   * @Fetcher()
   */
  private $tags;

  /**
   * @var DateTime
   *
   * @ORM\Column(type="datetime")
   * @Fetcher()
   */
  private $spentAt;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return float
   */
  public function getValue(): float
  {
    return $this->value;
  }

  /**
   * @param float $value
   */
  public function setValue(float $value): void
  {
    $this->value = $value;
  }

  /**
   * @return Currency
   */
  public function getCurrency(): Currency
  {
    return $this->currency;
  }

  /**
   * @param Currency $currency
   */
  public function setCurrency(Currency $currency): void
  {
    $this->currency = $currency;
  }

  /**
   * @return Category
   */
  public function getCategory(): Category
  {
    return $this->category;
  }

  /**
   * @param Category $category
   */
  public function setCategory(Category $category): void
  {
    $this->category = $category;
  }

  /**
   * @return Tag[]
   */
  public function getTags()
  {
    return $this->tags;
  }

  /**
   * @param Tag[] $tags
   */
  public function setTags(array $tags): void
  {
    $this->tags = $tags;
  }

  /**
   * @return DateTime
   */
  public function getSpentAt(): DateTime
  {
    return $this->spentAt;
  }

  /**
   * @param DateTime $spentAt
   */
  public function setSpentAt(DateTime $spentAt): void
  {
    $this->spentAt = $spentAt;
  }

  /**
   * @return User
   */
  public function getUser(): User
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser(User $user): void
  {
    $this->user = $user;
  }
}
