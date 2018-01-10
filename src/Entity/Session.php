<?php

namespace App\Entity;

use App\Service\Session\Bag\AttributeBag;
use App\Service\Session\Bag\FlashBag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
{
  /**
   * @ORM\Id
   * @ORM\Column(type="string", length=100)
   */
  private $id;

  /**
   * @var User
   * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sessions")
   * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
   */
  private $user;

  /**
   * @var string
   * @ORM\Column(type="string", length=40)
   */
  private $ip;

  /**
   * @var bool
   * @ORM\Column(type="boolean")
   */
  private $isActive;

  /**
   * @var string
   * @ORM\Column(type="string", length=3)
   */
  private $countryCode;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime")
   */
  private $createdAt;

  /**
   * @var \DateTime
   * @ORM\Column(type="datetime")
   */
  private $last_action_at;

  /**
   * @var object
   * @ORM\Column(type="json")
   */
  private $attributes_bag;

  /**
   * @var object
   * @ORM\Column(type="json")
   */
  private $flash_bag;

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
   * @return User|null
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User|null $user
   */
  public function setUser(User $user = null)
  {
    $this->user = $user;
  }

  /**
   * @return string
   */
  public function getIp()
  {
    return $this->ip;
  }

  /**
   * @param string $ip
   */
  public function setIp($ip)
  {
    $this->ip = $ip;
  }

  /**
   * @return bool
   */
  public function isActive()
  {
    return $this->isActive;
  }

  /**
   * @param bool $isActive
   */
  public function setIsActive($isActive)
  {
    $this->isActive = $isActive;
  }

  /**
   * @return string
   */
  public function getCountryCode()
  {
    return $this->countryCode;
  }

  /**
   * @param string $countryCode
   */
  public function setCountryCode($countryCode)
  {
    $this->countryCode = $countryCode;
  }

  /**
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * @param \DateTime $createdAt
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;
  }

  /**
   * @return \DateTime
   */
  public function getLastActionAt()
  {
    return $this->last_action_at;
  }

  /**
   * @param \DateTime $last_action_at
   */
  public function setLastActionAt($last_action_at)
  {
    $this->last_action_at = $last_action_at;
  }

  public function updateLastAction()
  {
    $this->last_action_at = new \DateTime('now');
  }

  /**
   * @return object
   */
  public function getAttributesBag()
  {
    return json_decode($this->attributes_bag);
  }

  /**
   * @param AttributeBag $attributes_bag
   */
  public function setAttributesBag($attributes_bag = null)
  {
    $this->attributes_bag = json_encode($attributes_bag);
  }

  /**
   * @return object
   */
  public function getFlashBag()
  {
    return json_decode($this->flash_bag);
  }

  /**
   * @param FlashBag $flash_bag
   */
  public function setFlashBag($flash_bag = null)
  {
    $this->flash_bag = json_encode($flash_bag);
  }
}
