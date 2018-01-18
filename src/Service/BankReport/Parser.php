<?php

namespace App\Service\BankReport;

class Parser
{
  /**
   * @param string $fileName
   * @param string $bank
   * @return ParsedResult|null
   */
  public static function parse($fileName, $bank)
  {
    if (!BankFactory::exists($bank)) {
      return null;
    }

    $parser = BankFactory::get($bank);
    return $parser->parse($fileName);
  }
}
