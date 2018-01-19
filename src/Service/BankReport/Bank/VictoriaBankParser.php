<?php

namespace App\Service\BankReport\Bank;

use App\Service\BankReport\ParsedResult;
use App\Service\BankReport\ParsedResultItem;
use Smalot\PdfParser\Parser as PdfParser;

class VictoriaBankParser implements BankParserInterface
{
  /**
   * @param string $fileName
   * @return ParsedResult
   */
  public function parse($fileName)
  {
    $parser = new PdfParser();
    $pdf = $parser->parseFile($fileName);

    $text = $pdf->getText();
    $output = [];
    $text = preg_replace('/\s/', ' ', $text);
    preg_match_all(
      "/(\d{2}\/\d{2}\/\d{2})" . // date
      "\s+" .
      "(\d{2}\/\d{2}\/\d{2})*?" . // date (PDF VB problem)
      "\s*(.*)" . // transaction description
      "(-(?:\d|\s|\.)+)" . // amount in currency
      "([A-Z]{3})" . // currency
      "\s+" .
      "(\d{2}\/\d{2}\/\d{2})*?" . // transaction date
      "\s+" .
      "(-?(?:\d|\s)+\.\d{2})" . // fee
      "\s+" .
      "(-(?:\d|\s)+\.\d{2})" . // amount in Contract Currency
      "/muU",
      $text,
      $output
    );

    return $this->buildParsedResult($output);
  }

  /**
   * @param $result
   * @return ParsedResult|null
   */
  public function buildParsedResult($result)
  {
    if (!is_array($result) || count($result[0]) === 0) {
      return null;
    }

    list(
      $matches,
      $dates,
      $datePdfFixers,
      $descriptions,
      $currencyAmounts,
      $currencyCodes,
      $transactionDates,
      $fees,
      $amounts
      ) = $result;

    $parsedResult = new ParsedResult();
    foreach ($matches as $matchKey => $match) {
      $resultDate = \DateTime::createFromFormat('d/m/y', $dates[$matchKey])->setTime(12, 0, 0);
      $parsedResultItem = new ParsedResultItem();
      $parsedResultItem->setDateTime($resultDate)
        ->setDescription($descriptions[$matchKey])
        ->setCurrencyAmount($currencyAmounts[$matchKey])
        ->setCurrencyCode($currencyCodes[$matchKey])
        ->setAmount($amounts[$matchKey]);

      $parsedResult->append($parsedResultItem);
    }

    return $parsedResult;
  }
}
