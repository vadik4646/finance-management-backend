<?php

namespace App\Service\BankReport\Filler;

use App\Service\BankReport\ParsedResult;

interface FillerInterface
{
  /**
   * @param ParsedResult $financialReport
   */
  public function fill(ParsedResult $financialReport);
}
