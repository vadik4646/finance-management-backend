<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Token
{
  use CreatedAt;
  use UpdatedAt;

  /**
   * @ORM\Id
   * @ORM\Column(type="string", length=64)
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   */
  private $user;

  /**
   * @ORM\Column(type="string", length=200)
   */
  private $device;

  /**
   * @ORM\Column(type="string", length=3)
   */
  private $country;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastActionAt;

  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $id
   */
  public function setId($id): void
  {
    $this->id = $id;
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
  public function setUser($user): void
  {
    $this->user = $user;
  }

  /**
   * @return string
   */
  public function getDevice()
  {
    return $this->device;
  }

  /**
   * @param string $device
   */
  public function setDevice($device): void
  {
    $this->device = $device;
  }

  /**
   * @return string
   */
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * @param string $country
   */
  public function setCountry($country): void
  {
    $this->country = $country;
  }

  /**
   * @ORM\PrePersist()
   */
  public function setDateTimeValues()
  {
    $now = new \DateTime();
    $this->lastActionAt = $now;
    $this->createdAt = $now;
  }

  /**
   * @return mixed
   */
  public function getLastActionAt()
  {
    return $this->lastActionAt;
  }

  /**
   * @param mixed $lastActionAt
   */
  public function setLastActionAt($lastActionAt): void
  {
    $this->lastActionAt = $lastActionAt;
  }
}
