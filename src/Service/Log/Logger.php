<?php

namespace App\Service\Log;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Monolog\Logger as MonologLogger;

class Logger
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var \Monolog\Logger
   */
  private $monologLogger;

  /**
   * @var TokenStorage
   */
  private $tokenStorage;

  public function __construct(
    EntityManagerInterface $entityManager,
    \Monolog\Logger $monologLogger,
    TokenStorage $tokenStorage
  ) {
    $this->entityManager = $entityManager;
    $this->monologLogger = $monologLogger;
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * System is unusable.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function emergency($message, $source, array $params = [])
  {
    $this->monologLogger->emergency($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::EMERGENCY));
  }

  /**
   * Action must be taken immediately.
   *
   * Example: Entire website down, database unavailable, etc. This should
   * trigger the SMS alerts and wake you up.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function alert($message, $source, array $params = [])
  {
    $this->monologLogger->alert($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::ALERT));
  }

  /**
   * Critical conditions.
   *
   * Example: Application component unavailable, unexpected exception.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function critical($message, $source, array $params = [])
  {
    $this->monologLogger->critical($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::CRITICAL));
  }

  /**
   * Runtime errors that do not require immediate action but should typically
   * be logged and monitored.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function error($message, $source, array $params = [])
  {
    $this->monologLogger->error($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::ERROR));
  }

  /**
   * Exceptional occurrences that are not errors.
   *
   * Example: Use of deprecated APIs, poor use of an API, undesirable things
   * that are not necessarily wrong.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function warning($message, $source, array $params = [])
  {
    $this->monologLogger->warning($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::WARNING));
  }

  /**
   * Normal but significant events.
   *
   * @param string $message
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function notice($message, $source, array $params = [])
  {
    $this->monologLogger->notice($message, array_merge($params, ['source' => $source]));
    $this->create($message, $source, $params, MonologLogger::getLevelName(MonologLogger::NOTICE));
  }

  /**
   * Logs with an arbitrary level.
   *
   * @param string $message
   * @param string $type
   * @param string $source
   * @param array  $params
   *
   * @return void
   */
  public function log($message, $type, $source, array $params = [])
  {
    $this->create($message, $source, $params, $type);
    $this->{strtolower($type)}($message, $source, $params);
  }

  /**
   * @param string $debugLink
   */
  public function addDebugLink($debugLink)
  {
    $this->monologLogger->warning('Debug link: <' . $debugLink . '|' . $debugLink . '>');
  }

  /**
   * @param string $message
   * @param string $source
   * @param array  $params
   * @param string $type
   */
  private function create($message, $source, $params, $type)
  {
    $log = new Log();
    $log->setMessage($message);
    $log->setUser($this->tokenStorage->getToken()->getUser());
    $log->setParams(json_encode($params));
    $log->setType($type);
    $log->setSource($source);

    $this->entityManager->persist($log);
    $this->entityManager->flush();
  }
}
