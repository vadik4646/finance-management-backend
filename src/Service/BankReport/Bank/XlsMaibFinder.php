<?php

namespace App\Service\BankReport\Bank;

use App\Service\BankReport\ParsedResultItem;
use DateTime;

class XlsMaibFinder
{
  private $xlsColumnNames = ['Data', 'Ora', 'Tip Tranzacţie', 'Destinaţia Plăţii', 'Suma', 'Valuta', 'Statut'];

  /**
   * @param array $rows
   * @return ParsedResultItem[]|null
   */
  public function find($rows)
  {
    foreach ($rows as $index => $row) {
      $isTableHeaderRow = array_values(array_intersect($row, $this->xlsColumnNames)) == $this->xlsColumnNames;
      if ($isTableHeaderRow) {
        $columnIndexes = $this->getColumnIndexes($row);
        $dataRows = array_slice($rows, $index + 1);

        return $this->extract($columnIndexes, $dataRows);
      }
    }

    return null;
  }

  /**
   * @param array $row
   * @return array
   */
  private function getColumnIndexes($row)
  {
    $columnIndexes = [];
    foreach ($this->xlsColumnNames as $xlsColumnName) {
      $columnIndexes[$xlsColumnName] = array_search($xlsColumnName, $row);
    }

    return $columnIndexes;
  }

  /**
   * @param array $columnIndexes
   * @param array $rows
   * @return ParsedResultItem[]
   */
  private function extract($columnIndexes, $rows)
  {
    $parsedResultItems = [];
    foreach ($rows as $row) {
      if (!array_filter($row)) {
        continue;
      }

      $resultDate = DateTime::createFromFormat(
        'd/m/Y H:i:s',
        $row[$columnIndexes['Data']] . ' ' . $row[$columnIndexes['Ora']]
      );

      $parsedResultItem = new ParsedResultItem();
      $parsedResultItem->setDescription($row[$columnIndexes['Destinaţia Plăţii']]);
      $parsedResultItem->setType($row[$columnIndexes['Tip Tranzacţie']]);
      $parsedResultItem->setDateTime($resultDate);
      $parsedResultItem->setCurrencyAmount($row[$columnIndexes['Suma']]);
      $parsedResultItem->setCurrencyCode($row[$columnIndexes['Valuta']]);

      $parsedResultItems[] = $parsedResultItem;
    }

    return $parsedResultItems;
  }
}
