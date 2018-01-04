<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @var string
   * @ORM\Id
   * @ORM\Column(type="string", length=3)
   */
  private $code;

  /**
   * @var string
   * @ORM\Column(type="string", length=100)
   */
  private $name;

  /**
   * @var Rate[]
   * @ORM\OneToMany(targetEntity="App\Entity\Rate", mappedBy="currency")
   */
  private $rates;

  /**
   * @return string
   */
  public function getCode(): string
  {
    return $this->code;
  }

  /**
   * @param string $code
   */
  public function setCode(string $code): void
  {
    $this->code = $code;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  /**
   * @return Rate[]
   */
  public function getRates(): array
  {
    return $this->rates;
  }
}
