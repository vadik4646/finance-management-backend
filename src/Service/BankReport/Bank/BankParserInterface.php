<?php

namespace App\Service\BankReport\Bank;

use App\Service\BankReport\ParsedResult;

interface BankParserInterface
{
  /**
   * @param string $fileContent
   * @return ParsedResult
   */
  public function parse($fileContent);
}
