<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @var string
   * @ORM\Id
   * @ORM\Column(type="string", length=3)
   * @Assert\NotBlank()
   * @Assert\Currency()
   * @Fetcher()
   */
  private $code;

  /**
   * @var string
   * @ORM\Column(type="string", length=100)
   * @Assert\NotBlank()
   * @Fetcher()
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
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param string $code
   */
  public function setCode($code)
  {
    $this->code = $code;
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
   * @return Rate[]
   */
  public function getRates()
  {
    return $this->rates;
  }

  public function __toString()
  {
    return $this->getCode() . ' ' . $this->getName();
  }
}
