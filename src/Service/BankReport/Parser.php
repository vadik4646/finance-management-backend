<?php

namespace App\Service\BankReport;

class Parser
{
  /**
   * @param string $fileName
   * @param string $bank
   * @return ParsedResult|null
   */
  public function parse($fileName, $bank)
  {
    $bankFactory = new BankFactory();
    if (!$bankFactory->exists($bank)) {
      return null;
    }

    $parser = $bankFactory->get($bank);
    return $parser->parse($fileName);
  }
}
