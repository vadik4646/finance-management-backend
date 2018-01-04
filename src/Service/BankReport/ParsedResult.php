<?php

namespace App\Service\BankReport;

class ParsedResult
{
  /** @var ParsedResultItem[] */
  private $results = [];

  /**
   * @param ParsedResultItem $parsedResultItem
   */
  public function append(ParsedResultItem $parsedResultItem)
  {
    $this->results[] = $parsedResultItem;
  }

  /**
   * @return ParsedResultItem[]
   */
  public function getResults()
  {
    return $this->results;
  }

  /**
   * @return array
   */
  public function exportAll()
  {
    $exportedResults = [];
    foreach ($this->results as $result) {
      $exportedResults[] = $result->export();
    }

    return $exportedResults;
  }

  /**
   * @return array
   */
  public function getGroupedResults()
  {
    $groupedByCurrency = [];
    foreach ($this->results as $result) {
      if (!array_key_exists($result->getCurrencyCode(), $groupedByCurrency)) {
        $groupedByCurrency[$result->getCurrencyCode()] = [];
      }

      $groupedByCurrency[$result->getCurrencyCode()][] = $result;
    }

    return $groupedByCurrency;
  }
}
