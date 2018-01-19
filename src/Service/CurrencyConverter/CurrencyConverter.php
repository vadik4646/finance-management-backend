<?php

namespace App\Service\CurrencyConverter;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Service\BankReport\ExchangeRateProvider;
use DateTime;

class CurrencyConverter
{
  /**
   * @var ExchangeRateProvider
   */
  private $exchangeRateProvider;

  /**
   * @var Currency
   */
  private $currency;

  /**
   * @var DateTime
   */
  private $date;

  /**
   * @var Rate
   */
  private $primaryRate;

  public function __construct(ExchangeRateProvider $exchangeRateProvider, Currency $currency, DateTime $date)
  {
    $this->exchangeRateProvider = $exchangeRateProvider;
    $this->currency = $currency;
    $this->date = $date;
    $this->primaryRate = $this->exchangeRateProvider->get($date, $currency);
  }

  /**
   * @param Currency $currency
   * @param float    $value
   * @return null|float
   */
  public function to(Currency $currency, $value = 1.00)
  {
    if (!$rate = $this->exchangeRateProvider->get($this->date, $currency)) {
      return null;
    }

    return $this->convert($rate, $value);
  }

  /**
   * @param Rate  $rate
   * @param float $value
   * @return float|null
   */
  private function convert(Rate $rate, $value)
  {
    if (!$this->primaryRate->getValue()) {
      return null;
    }

    return $value * $this->primaryRate->getValue() / $rate->getValue();
  }
}
