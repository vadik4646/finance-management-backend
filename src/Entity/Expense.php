<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenseRepository")
 * @ORM\HasLifecycleCallbacks()
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
   * @Assert\NotBlank()
   * @Assert\Type(type="float")
   * @Fetcher()
   */
  private $value;

  /**
   * @var Currency
   *
   * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
   * @ORM\JoinColumn(referencedColumnName="code", name="currency_code")
   * @Fetcher()
   * @Assert\NotBlank()
   */
  private $currency;

  /**
   * @var Category
   *
   * @ORM\ManyToOne(targetEntity="Category")
   * @Assert\NotBlank()
   * @Fetcher()
   */
  private $category;

  /**
   * @var Tag[]
   *
   * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="expenses")
   * @Fetcher()
   */
  private $tags;

  /**
   * @var DateTime
   *
   * @ORM\Column(type="datetime")
   * @Assert\NotBlank()
   * @Assert\DateTime()
   * @Fetcher()
   */
  private $spentAt;

  public function __construct()
  {
    $this->tags = new ArrayCollection();
  }

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
   * @return Currency
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  /**
   * @param Currency $currency
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  /**
   * @return Category
   */
  public function getCategory()
  {
    return $this->category;
  }

  /**
   * @param Category $category
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
   * @param Tag $tag
   */
  public function addTag(Tag $tag)
  {
    $this->tags->add($tag);
  }

  /**
   * @param Tag[] $tags
   */
  public function setTags($tags)
  {
    $this->tags = $tags;
  }

  /**
   * @return DateTime
   */
  public function getSpentAt()
  {
    return $this->spentAt;
  }

  /**
   * @param DateTime $spentAt
   */
  public function setSpentAt($spentAt)
  {
    $this->spentAt = $spentAt;
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
