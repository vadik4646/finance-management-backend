<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\Type\CustomizationKey;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomizationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Customization
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
   * @ORM\Column(type="string", length=50)
   * @Fetcher()
   * @Assert\Choice({CustomizationKey::CURRENCY})
   * @Assert\NotBlank()
   */
  private $name;

  /**
   * @ORM\Column(type="string", length=250)
   * @Fetcher()
   * @Assert\NotBlank()
   */
  private $value;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $user;

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
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser(User $user)
  {
    $this->user = $user;
  }
}
