<?php

namespace App\Service\BankReport;

use App\Service\BankReport\Bank\BankParserInterface;
use App\Service\BankReport\Bank\MoldinconBankParser;
use App\Service\BankReport\Bank\MaibParser;
use App\Service\BankReport\Bank\VictoriaBankParser;

class BankFactory
{
  const VICTORIA_BANK        = 'victoria_bank';
  const MOLDOVA_AGROIND_BANK = 'moldova_agroind_bank';
  const MOLDINCON_BANK       = 'moldincon_bank';

  private static $bankMap = [
    self::VICTORIA_BANK        => VictoriaBankParser::class,
    self::MOLDINCON_BANK       => MoldinconBankParser::class,
    self::MOLDOVA_AGROIND_BANK => MaibParser::class,
  ];

  /**
   * @param string $bank
   * @return BankParserInterface|null
   */
  public function get($bank)
  {
    return array_key_exists($bank, self::$bankMap) ? new self::$bankMap[$bank]() : null;
  }

  /**
   * @return array
   */
  public function map()
  {
    return [self::VICTORIA_BANK, self::MOLDOVA_AGROIND_BANK, self::MOLDINCON_BANK];
  }

  /**
   * @param string $bank
   * @return array
   */
  public function exists($bank)
  {
    return array_key_exists($bank, self::$bankMap);
  }
}
