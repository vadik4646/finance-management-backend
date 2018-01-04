<?php

namespace App\Service\Session;

use App\Service\Authentication\TokenProvider;
use App\Service\Session\Bag\AttributeBag;
use App\Service\Session\Bag\BagType;
use App\Service\Session\Bag\FlashBag;
use App\Service\Session\Bag\MetadataBag;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use App\Entity\Session as SessionEntity;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class SessionStorage implements SessionStorageInterface
{
  private $entityManager;
  private $tokenProvider;
  private $requestStack;

  /** @var SessionEntity|null */
  private $session;

  /** @var AttributeBag */
  private $attributeBag;

  /** @var FlashBag */
  private $flashBag;

  /** @var MetadataBag */
  private $metadataBag;

  public function __construct(
    EntityManagerInterface $entityManager,
    TokenProvider $tokenProvider,
    RequestStack $requestStack,
    MetadataBag $metadataBag,
    AttributeBag $attributeBag,
    FlashBag $flashBag
  ) {
    $this->entityManager = $entityManager;
    $this->tokenProvider = $tokenProvider;
    $this->requestStack = $requestStack;
    $this->metadataBag = $metadataBag;
    $this->attributeBag = $attributeBag;
    $this->flashBag = $flashBag;
  }

  /**
   * Starts the session.
   *
   * @return bool True if started
   *
   * @throws \RuntimeException if something goes wrong starting the session
   */
  public function start()
  {
    if ($this->session) {
      return true;
    }

    $sessionId = $this->getIdFromRequest();
    $sessionRepository = $this->entityManager->getRepository(SessionEntity::class);
    if (!$sessionId || !$session = $sessionRepository->find($sessionId)) {
      $session = $this->create();
      $metadataProperties = $this->generateMetadataBagProperties();
    } else {
      $metadataProperties = $this->getMetadataBagPropertiesFromSession($session);
    }

    $this->metadataBag->initialize($metadataProperties);
    $session->updateLastAction();
    $this->session = $session;

    return true;
  }

  /**
   * Checks if the session is started.
   *
   * @return bool True if started, false otherwise
   */
  public function isStarted()
  {
    return boolval($this->session);
  }

  /**
   * Returns the session ID.
   *
   * @return string The session ID or empty
   */
  public function getId()
  {
    return $this->session->getId();
  }

  /**
   * Sets the session ID.
   *
   * @param string $id
   */
  public function setId($id)
  {
    $this->session->setId($id);
  }

  /**
   * Returns the session name.
   *
   * @return mixed The session name
   */
  public function getName()
  {
    return TokenProvider::TOKEN_KEY;
  }

  /**
   * Sets the session name.
   *
   * @param string $name
   */
  public function setName($name)
  {
    return;
  }

  /**
   * Regenerates id that represents this storage.
   *
   * This method must invoke session_regenerate_id($destroy) unless
   * this interface is used for a storage object designed for unit
   * or functional testing where a real PHP session would interfere
   * with testing.
   *
   * Note regenerate+destroy should not clear the session data in memory
   * only delete the session data from persistent storage.
   *
   * Care: When regenerating the session ID no locking is involved in PHP's
   * session design. See https://bugs.php.net/bug.php?id=61470 for a discussion.
   * So you must make sure the regenerated session is saved BEFORE sending the
   * headers with the new ID. Symfony's HttpKernel offers a listener for this.
   * See Symfony\Component\HttpKernel\EventListener\SaveSessionListener.
   * Otherwise session data could get lost again for concurrent requests with the
   * new ID. One result could be that you get logged out after just logging in.
   *
   * @param bool $destroy  Destroy session when regenerating?
   * @param int  $lifetime Sets the cookie lifetime for the session cookie. A null value
   *                       will leave the system settings unchanged, 0 sets the cookie
   *                       to expire with browser session. Time is in seconds, and is
   *                       not a Unix timestamp.
   *
   * @return bool True if session regenerated, false if error
   *
   * @throws \RuntimeException If an error occurs while regenerating this storage
   */
  public function regenerate($destroy = false, $lifetime = null)
  {
    if ($destroy) {
      $this->delete();
      $this->session = $this->create();
    } else {
      $this->session->setId($this->generateUniqueSessionId());
      $this->getMetadataBag()->createdAt = new DateTime();
    }

    return true;
  }

  /**
   * Force the session to be saved and closed.
   *
   * This method must invoke session_write_close() unless this interface is
   * used for a storage object design for unit or functional testing where
   * a real PHP session would interfere with testing, in which case
   * it should actually persist the session data if required.
   *
   * @throws \RuntimeException if the session is saved without being started, or if the session
   *                           is already closed
   */
  public function save()
  {
    if (!$this->session) {
      throw new \RuntimeException();
    }

    $this->session->setFlashBag($this->flashBag);
    $this->session->setAttributesBag($this->attributeBag);

    $this->session->setCountryCode($this->metadataBag->countryCode);
    $this->session->setIsActive($this->metadataBag->isActive);
    $this->session->setIp($this->metadataBag->ip);
    $this->session->setUser($this->metadataBag->user);
    $this->session->setCreatedAt($this->metadataBag->createdAt);
    $this->session->setLastActionAt($this->metadataBag->lastActionAt);

    $this->entityManager->persist($this->session);
    $this->entityManager->flush();
  }

  /**
   * Clear all session data in memory.
   */
  public function clear()
  {

  }

  /**
   * Gets a SessionBagInterface by name.
   *
   * @param string $name
   *
   * @return SessionBagInterface
   *
   * @throws InvalidArgumentException If the bag does not exist
   */
  public function getBag($name)
  {
    switch ($name) {
      case BagType::TYPE_FLASH:
        return $this->flashBag;
        break;
      case BagType::TYPE_ATTRIBUTE:
        return $this->attributeBag;
        break;
      case BagType::TYPE_METADATA:
        return $this->metadataBag;
        break;
    }

    throw new InvalidArgumentException();
  }

  /**
   * Registers a SessionBagInterface for use.
   */
  public function registerBag(SessionBagInterface $bag)
  {
    switch ($bag->getName()) {
      case BagType::TYPE_FLASH:
        $this->flashBag = $bag;
        break;
      case BagType::TYPE_ATTRIBUTE:
        $this->attributeBag = $bag;
        break;
      case BagType::TYPE_METADATA:
        $this->metadataBag = $bag;
        break;
    }
  }

  /**
   * @return MetadataBag
   */
  public function getMetadataBag()
  {
    return $this->metadataBag;
  }

  /**
   * @return AttributeBag
   */
  public function getAttributesBag()
  {
    return $this->attributeBag;
  }

  /**
   * @return FlashBag
   */
  public function getFlashBag()
  {
    return $this->flashBag;
  }

  /**
   * @return SessionEntity
   */
  private function create()
  {
    $id = $this->generateUniqueSessionId();

    $session = new SessionEntity();
    $session->setId($id);

    $metadataProperties = $this->generateMetadataBagProperties();
    $this->metadataBag->initialize($metadataProperties);

    return $session;
  }

  private function delete()
  {
    if ($this->session) {
      $this->entityManager->remove($this->session);
    }
  }

  /**
   * @return string
   */
  private function generateUniqueSessionId()
  {
    $id = $this->generateId();
    $sessionRepository = $this->entityManager->getRepository(SessionEntity::class);
    while ($sessionRepository->find($id)) {
      $id = $this->generateId();
    }

    return $id;
  }

  /**
   * @return string
   */
  private function generateId()
  {
    $ip = $this->requestStack->getMasterRequest()->getClientIp();
    return hash('sha256', session_create_id() . $ip . rand());
  }

  /**
   * @return array
   */
  private function generateMetadataBagProperties()
  {
    $now = new DateTime();
    $masterRequest = $this->requestStack->getMasterRequest();

    return [
      'isActive' => true,
      'createdAt' => $now,
      'lastActionAt' => $now,
      'countryCode' => 'MD',
      'user' => null,
      'ip' => $masterRequest->getClientIp()
    ];
  }

  /**
   * @param SessionEntity $session
   * @return array
   */
  private function getMetadataBagPropertiesFromSession(SessionEntity $session)
  {
    $masterRequest = $this->requestStack->getMasterRequest();

    return [
      'isActive' => $session->isActive(),
      'createdAt' => $session->getCreatedAt(),
      'lastActionAt' => $session->getLastActionAt(),
      'countryCode' => $session->getCountryCode(),
      'user' => $session->getUser(),
      'ip' => $masterRequest->getClientIp()
    ];
  }

  /**
   * @return string
   */
  private function getIdFromRequest()
  {
    $masterRequest = $this->requestStack->getMasterRequest();
    return $this->tokenProvider->get($masterRequest);
  }
}
