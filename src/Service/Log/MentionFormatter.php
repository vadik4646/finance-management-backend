<?php

namespace App\Service\Log;

use App\Utils\Type\LogType;
use Monolog\Formatter\FormatterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MentionFormatter implements FormatterInterface
{
  /**
   * @var []
   */
  private $frontendSlackIds;

  /**
   * @var []
   */
  private $backendSlackIds;

  public function __construct($frontendSlackIds, $backendSlackIds)
  {
    $this->frontendSlackIds = array_filter(explode(',', $frontendSlackIds));
    $this->backendSlackIds = array_filter(explode(',', $backendSlackIds));
  }

  /**
   * Formats a log record.
   *
   * @param  array $record A record to format
   * @return mixed The formatted record
   */
  public function format(array $record)
  {
    if (!$this->hasNotFoundException($record)) {
      $record['message'] = $this->buildMessage($record['message'], $this->getSource($record));
    }

    return $record;
  }

  /**
   * Formats a set of log records.
   *
   * @param  array $records A set of records to format
   * @return mixed The formatted set of records
   */
  public function formatBatch(array $records)
  {
    foreach ($records as &$record) {
      $record = $this->format($record);
    }

    return $records;
  }

  /**
   * @param array $record
   * @return string
   */
  private function getSource($record)
  {
    return isset($record['context']['source']) ? $record['context']['source'] : LogType::BACK_END;
  }

  /**
   * @param array $record
   * @return bool
   */
  private function hasNotFoundException($record)
  {
    return isset($record['context']['exception']) && $record['context']['exception'] instanceof NotFoundHttpException;
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
      $mentions = $this->buildMentions($this->frontendSlackIds);
    } elseif ($source === LogType::BACK_END) {
      $mentions = $this->buildMentions($this->backendSlackIds);
    }

    return implode(', ', $mentions) . ' ' . $message;
  }

  /**
   * @param array $slackIds
   * @return array
   */
  private function buildMentions($slackIds)
  {
    $mentions = [];
    foreach ($slackIds as $slackId) {
      $mentions[] = '<@' . $slackId . '>';
    }

    return $mentions;
  }
}
