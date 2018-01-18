<?php

namespace App\Service\Session\Bag;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class MetadataBag implements SessionBagInterface
{
  /** @var null|User */
  public $user = null;

  /** @var string */
  public $ip = '';

  /** @var string */
  public $countryCode = '';

  /** @var bool */
  public $isActive = true;

  /** @var \DateTime */
  public $createdAt;

  /** @var \DateTime */
  public $lastActionAt;

  /**
   * Gets this bag's name.
   *
   * @return string
   */
  public function getName()
  {
    return BagType::TYPE_METADATA;
  }

  /**
   * Initializes the Bag.
   */
  public function initialize(array &$array)
  {
    foreach ($array as $propertyKey => $propertyValue) {
      $this->{$propertyKey} = $propertyValue;
    }
  }

  /**
   * Gets the storage key for this bag.
   *
   * @return string
   */
  public function getStorageKey()
  {
    return '';
  }

  /**
   * Clears out data from bag.
   *
   * @return mixed Whatever data was contained
   */
  public function clear()
  {
    $bagSnapshot = clone $this;

    $this->user = null;
    $this->ip = null;
    $this->countryCode = null;
    $this->createdAt = null;
    $this->lastActionAt = null;

    return (array)$bagSnapshot;
  }

  public function getCreated()
  {
    return $this->createdAt->getTimestamp();
  }

  public function getLastUsed()
  {
    return $this->lastActionAt->getTimestamp();
  }

  public function getLifetime()
  {
    return $this->lastActionAt->getTimestamp();
  }
}
