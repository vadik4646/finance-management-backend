<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncomeRepository")
 * @ORM\HasLifecycleCallbacks()
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
   * @Assert\NotBlank()
   * @Assert\Type(type="float")
   */
  private $value;

  /**
   * @var Currency|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
   * @ORM\JoinColumn(referencedColumnName="code", name="currency_code")
   * @Assert\NotBlank()
   */
  private $currency;

  /**
   * @var Category|null
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Category")
   * @Assert\NotBlank()
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
   * @Assert\NotBlank()
   * @Assert\DateTime()
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
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return float
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @param float $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * @return Currency|null
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  /**
   * @param Currency|null $currency
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  /**
   * @return Category|null
   */
  public function getCategory()
  {
    return $this->category;
  }

  /**
   * @param Category|null $category
   */
  public function setCategory($category)
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
  public function setTags($tags)
  {
    $this->tags = $tags;
  }

  /**
   * @return \DateTime
   */
  public function getIncomeAt()
  {
    return $this->incomeAt;
  }

  /**
   * @param \DateTime $incomeAt
   */
  public function setIncomeAt($incomeAt)
  {
    $this->incomeAt = $incomeAt;
  }

  /**
   * @return User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }
}
