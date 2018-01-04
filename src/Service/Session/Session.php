<?php

namespace App\Service\Session;

use App\Entity\User;
use App\Service\Session\Bag\AttributeBag;
use App\Service\Session\Bag\BagType;
use App\Service\Session\Bag\FlashBag;
use App\Service\Session\Bag\MetadataBag;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Session implements SessionInterface
{
  private $storage;
  private $isClosed = false;

  public function __construct(SessionStorage $session_storage)
  {
    $this->storage = $session_storage;
  }

  /**
   * Starts the session storage.
   *
   * @return bool True if session started
   *
   * @throws \RuntimeException if session fails to start
   */
  public function start()
  {
    if ($this->storage->isStarted()) {
      return true;
    }

    return $this->storage->start();
  }

  /**
   * Returns the session ID.
   *
   * @return string The session ID
   */
  public function getId()
  {
    return $this->storage->getId();
  }

  /**
   * Sets the session ID.
   *
   * @param string $id
   */
  public function setId($id)
  {
    $this->storage->setId($id);
  }

  /**
   * Returns the session name.
   *
   * @return mixed The session name
   */
  public function getName()
  {
    return $this->storage->getName();
  }

  /**
   * Sets the session name.
   *
   * @param string $name
   */
  public function setName($name)
  {
    $this->storage->setName($name);
  }

  /**
   * Invalidates the current session.
   *
   * Clears all session attributes and flashes and regenerates the
   * session and deletes the old session from persistence.
   *
   * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
   *                      will leave the system settings unchanged, 0 sets the cookie
   *                      to expire with browser session. Time is in seconds, and is
   *                      not a Unix timestamp.
   *
   * @return bool True if session invalidated, false if error
   */
  public function invalidate($lifetime = null)
  {
    $this->storage->clear();

    return $this->storage->start();
  }

  /**
   * Migrates the current session to a new session id while maintaining all
   * session attributes.
   *
   * @param bool $destroy  Whether to delete the old session or leave it to garbage collection
   * @param int  $lifetime Sets the cookie lifetime for the session cookie. A null value
   *                       will leave the system settings unchanged, 0 sets the cookie
   *                       to expire with browser session. Time is in seconds, and is
   *                       not a Unix timestamp.
   *
   * @return bool True if session migrated, false if error
   */
  public function migrate($destroy = false, $lifetime = null)
  {
    if (!$this->storage->isStarted()) {
      return $this->storage->start();
    }

    return $this->storage->regenerate();
  }

  /**
   * Force the session to be saved and closed.
   *
   * This method is generally not required for real sessions as
   * the session will be automatically saved at the end of
   * code execution.
   */
  public function save()
  {
    $this->storage->save();
  }

  /**
   * Checks if an attribute is defined.
   *
   * @param string $name The attribute name
   *
   * @return bool true if the attribute is defined, false otherwise
   */
  public function has($name)
  {
    if (isset($this->storage->getMetadataBag()->{$name})) {
      return $this->storage->getMetadataBag()->{$name};
    }

    if (property_exists($this->storage->getAttributesBag(), $name)) {
      return $this->storage->getAttributesBag()->{$name};
    }

    return null;
  }

  /**
   * Returns an attribute.
   *
   * @param string $name    The attribute name
   * @param mixed  $default The default value if not found
   *
   * @return mixed
   */
  public function get($name, $default = null)
  {
    return isset($this->storage->getMetadataBag()->{$name}) || isset($this->storage->getAttributesBag()->{$name});
  }

  /**
   * Sets an attribute.
   *
   * @param string $name
   * @param mixed  $value
   */
  public function set($name, $value)
  {
    if (property_exists($this->storage->getMetadataBag(), $name)) {
      $this->storage->getMetadataBag()->{$name} = $value;
    }

    $this->storage->getAttributesBag()->{$name} = $value;
  }

  /**
   * Returns attributes.
   *
   * @return array Attributes
   */
  public function all()
  {

  }

  /**
   * Sets attributes.
   *
   * @param array $attributes Attributes
   */
  public function replace(array $attributes)
  {
    // TODO: Implement replace() method.
  }

  /**
   * Removes an attribute.
   *
   * @param string $name
   *
   * @return mixed The removed value or null when it does not exist
   */
  public function remove($name)
  {
    // TODO: Implement remove() method.
  }

  /**
   * Clears all attributes.
   */
  public function clear()
  {
    // TODO: Implement clear() method.
  }

  /**
   * Checks if the session was started.
   *
   * @return bool
   */
  public function isStarted()
  {
    return !$this->isClosed && $this->storage->isStarted();
  }

  /**
   * Registers a SessionBagInterface with the session.
   */
  public function registerBag(SessionBagInterface $bag)
  {
    // TODO: Implement registerBag() method.
  }

  /**
   * Gets a bag instance by name.
   *
   * @param string $name
   *
   * @return SessionBagInterface
   */
  public function getBag($name)
  {
    switch ($name) {
      case BagType::TYPE_FLASH:
        return $this->storage->getFlashBag();
        break;
      case BagType::TYPE_ATTRIBUTE:
        return $this->storage->getAttributesBag();
        break;
      case BagType::TYPE_METADATA:
        return $this->storage->getMetadataBag();
        break;
    }
  }

  /**
   * Gets session meta.
   *
   * @return MetadataBag
   */
  public function getMetadataBag()
  {
    return $this->storage->getMetadataBag();
  }

  /**
   * @return AttributeBag
   */
  public function getAttributesBag()
  {
    return $this->storage->getAttributesBag();
  }

  /**
   * @return FlashBag
   */
  public function getFlashBag()
  {
    return $this->storage->getFlashBag();
  }

  /**
   * @param User $user
   */
  public function authenticate(User $user)
  {
    $this->storage->getMetadataBag()->user = $user;
  }

  public function close()
  {
    $this->isClosed = true;
  }
}
