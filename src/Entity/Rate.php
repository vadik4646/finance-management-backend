<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RateRepository")
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
   */
  private $date;

  /**
   * @var float
   * @ORM\Column(type="float", length=8)
   */
  private $value;

  /**
   * @var Currency
   * @ORM\ManyToOne(targetEntity="Currency")
   * @ORM\JoinColumn(name="currency_code", referencedColumnName="code")
   */
  private $currency;

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }

  /**
   * @return DateTime
   */
  public function getDate(): DateTime
  {
    return $this->date;
  }

  /**
   * @param DateTime $date
   */
  public function setDate(DateTime $date): void
  {
    $this->date = $date;
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
}
