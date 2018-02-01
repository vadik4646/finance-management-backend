<?php

namespace App\Service\Log;

use App\Entity\Log;
use App\Utils\Type\LogType;
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

  private $frontendSlackIds;

  private $backendSlackIds;

  public function __construct(
    EntityManagerInterface $entityManager,
    \Monolog\Logger $monologLogger,
    TokenStorage $tokenStorage,
    $frontendSlackIds,
    $backendSlackIds
  ) {
    $this->entityManager = $entityManager;
    $this->monologLogger = $monologLogger;
    $this->tokenStorage = $tokenStorage;
    $this->frontendSlackIds = array_filter(explode(',', $frontendSlackIds));
    $this->backendSlackIds = array_filter(explode(',', $backendSlackIds));
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
    $this->monologLogger->emergency($this->buildMessage($message, $source), $params);
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
    $this->monologLogger->alert($this->buildMessage($message, $source), $params);
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
    $this->monologLogger->critical($this->buildMessage($message, $source), $params);
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
    $this->monologLogger->error($this->buildMessage($message, $source), $params);
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
    $this->monologLogger->warning($this->buildMessage($message, $source), $params);
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
    $this->monologLogger->notice($this->buildMessage($message, $source), $params);
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

  /**
   * @param string $message
   * @param string $source
   * @return string
   */
  private function buildMessage($message, $source)
  {
    $mentions = [];
    if ($source === LogType::FRONT_END) {
      foreach ($this->frontendSlackIds as $frontendSlackId) {
        $mentions[] = '<@' . $frontendSlackId . '>';
      }
    }

    if ($source === LogType::BACK_END) {
      foreach ($this->backendSlackIds as $backendSlackId) {
        $mentions[] = '<@' . $backendSlackId . '>';
      }
    }

    return 'Source: ' . $source . ' ' . implode(', ', $mentions) . ' ' . $message;
  }
}
