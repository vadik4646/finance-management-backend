<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Rate
{
  use CreatedAt;
  use UpdatedAt;
  /**
   * @var integer
   * @ORM\Id()
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue()
   */
  private $id;

  /**
   * @var DateTime
   * @ORM\Column(type="date", length=100)
   * @Assert\NotBlank()
   * @Assert\Date()
   */
  private $date;

  /**
   * @var float
   * @ORM\Column(type="float", length=8)
   * @Assert\NotBlank()
   * @Assert\Type(type="float")
   */
  private $value;

  /**
   * @var Currency
   * @ORM\ManyToOne(targetEntity="Currency")
   * @ORM\JoinColumn(name="currency_code", referencedColumnName="code")
   * @Assert\NotBlank()
   * @Assert\Currency()
   */
  private $currency;

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
   * @return DateTime
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param DateTime $date
   */
  public function setDate($date)
  {
    $this->date = $date;
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
}
