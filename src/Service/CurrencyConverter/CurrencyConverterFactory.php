<?php

namespace App\Service\CurrencyConverter;

use App\Entity\Currency;
use App\Service\BankReport\ExchangeRateProvider;
use DateTime;

class CurrencyConverterFactory
{
  /**
   * @var ExchangeRateProvider
   */
  private $exchangeRateProvider;

  public function __construct(ExchangeRateProvider $exchangeRateProvider)
  {
    $this->exchangeRateProvider = $exchangeRateProvider;
  }

  /**
   * @param Currency $currency
   * @param DateTime $date
   * @return CurrencyConverter
   */
  public function build(Currency $currency, DateTime $date)
  {
    return new CurrencyConverter($this->exchangeRateProvider, $currency, $date);
  }
}
