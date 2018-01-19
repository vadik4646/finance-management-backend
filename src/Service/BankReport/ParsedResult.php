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
  public function export()
  {
    $exportedResults = [];
    foreach ($this->results as $result) {
      $exportedResults[] = $result->export();
    }

    return $exportedResults;
  }

  /**
   * @return ParsedResultItem[][]
   */
  public function getGroupedResults()
  {
    $groupedByCurrency = [];
    foreach ($this->results as $result) {
      if (!isset($groupedByCurrency[$result->getCurrencyCode()])) {
        $groupedByCurrency[$result->getCurrencyCode()] = [];
      }

      $groupedByCurrency[$result->getCurrencyCode()][] = $result;
    }

    return $groupedByCurrency;
  }
}
