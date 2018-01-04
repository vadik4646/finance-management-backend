<?php

namespace App\Service\BankReport\Bank;

use App\Service\BankReport\ParsedResult;
use App\Service\BankReport\ParsedResultItem;

class MaibParser implements BankParserInterface
{
  /**
   * @param string $fileName
   * @return ParsedResult
   */
  public function parse($fileName)
  {
    $excelReader = \PHPExcel_IOFactory::createReaderForFile($fileName)->load($fileName);
    $worksheetIterator = $excelReader->getWorksheetIterator();
    $parsedResult = new ParsedResult();
    foreach ($worksheetIterator as $worksheet) {
      foreach ($this->parseWorksheet($worksheet) as $parsedResultItem) {
        $parsedResult->append($parsedResultItem);
      }
    }

    return $parsedResult;
  }

  /**
   * @param \PHPExcel_Worksheet $worksheet
   * @return ParsedResultItem[]
   */
  private function parseWorksheet($worksheet)
  {
    $rows = $worksheet->toArray();
    $headerRowIndex = null;
    $xlsMaibFinder = new XlsMaibFinder();
    return $xlsMaibFinder->find($rows);
  }
}
