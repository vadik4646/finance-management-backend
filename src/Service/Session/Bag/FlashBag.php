<?php
/**
 * Created by PhpStorm.
 * User: vtabaran
 * Date: 12/21/17
 * Time: 5:34 PM
 */

namespace App\Service\Session\Bag;

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class FlashBag implements SessionBagInterface
{
  /**
   * Gets this bag's name.
   *
   * @return string
   */
  public function getName()
  {
    return BagType::TYPE_FLASH;
  }

  /**
   * Initializes the Bag.
   */
  public function initialize(array &$array)
  {
    // TODO: Implement initialize() method.
  }

  /**
   * Gets the storage key for this bag.
   *
   * @return string
   */
  public function getStorageKey()
  {
    return BagType::TYPE_FLASH . '_bag';
  }

  /**
   * Clears out data from bag.
   *
   * @return mixed Whatever data was contained
   */
  public function clear()
  {
    $bagSnapshot = clone $this;

    foreach (get_object_vars($this) as $propertyKey => $propertyValue) {
      unset($this[$propertyKey]);
    }

    return (array)$bagSnapshot;
  }

  public function peekAll()
  {
    return (array)$this;
  }

  /**
   * @return string
   */
  public function toJson()
  {
    return json_encode($this);
  }
}
