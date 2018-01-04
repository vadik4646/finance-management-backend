<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncomeRepository")
 */
class Income
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
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
   */
  private $value;

  /**
   * @var Currency|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
   * @ORM\JoinColumn(referencedColumnName="code", name="currency_code")
   */
  private $currency;

  /**
   * @var Category|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Category")
   */
  private $category;

  /**
   * @var Tag[]
   *
   * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
   */
  private $tags;

  /**
   * @var \DateTime
   *
   * @ORM\Column(type="datetime")
   */
  private $incomeAt;

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
   * @return Currency|null
   */
  public function getCurrency(): ?Currency
  {
    return $this->currency;
  }

  /**
   * @param Currency|null $currency
   */
  public function setCurrency(?Currency $currency): void
  {
    $this->currency = $currency;
  }

  /**
   * @return Category|null
   */
  public function getCategory(): ?Category
  {
    return $this->category;
  }

  /**
   * @param Category|null $category
   */
  public function setCategory(?Category $category): void
  {
    $this->category = $category;
  }

  /**
   * @return Tag[]
   */
  public function getTags(): array
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
   * @return \DateTime
   */
  public function getIncomeAt(): \DateTime
  {
    return $this->incomeAt;
  }

  /**
   * @param \DateTime $incomeAt
   */
  public function setIncomeAt(\DateTime $incomeAt): void
  {
    $this->incomeAt = $incomeAt;
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
