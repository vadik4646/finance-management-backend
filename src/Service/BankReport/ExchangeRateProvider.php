<?php

namespace App\Service\BankReport;

use App\Entity\Currency;
use App\Entity\Rate;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ExchangeRateProvider
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var ExchangeRateParser
   */
  private $exchangeRateParser;

  public function __construct(EntityManagerInterface $entityManager, ExchangeRateParser $exchangeRateParser)
  {
    $this->entityManager = $entityManager;
    $this->exchangeRateParser = $exchangeRateParser;
  }

  /**
   * @param DateTime $dateTime
   * @param Currency $currency
   * @return Rate|null
   */
  public function get(DateTime $dateTime, Currency $currency)
  {
    if ($rate = $this->entityManager->getRepository(Rate::class)->get($currency, $dateTime)) {
      return $rate;
    }

    return $this->parse($dateTime, $currency->getCode());
  }

  /**
   * @param DateTime $date
   * @param string   $currencyCode
   * @return Rate|null
   */
  private function parse(DateTime $date, $currencyCode)
  {
    $parsedRates = $this->exchangeRateParser->get($date);
    $rates = $this->fillStorage($parsedRates, $date);

    return isset($rates[$currencyCode]) ? $rates[$currencyCode] : null;
  }

  /**
   * @param array    $parsedRates
   * @param DateTime $date
   * @return Rate[]
   */
  private function fillStorage($parsedRates, DateTime $date)
  {
    $currencies = $this->entityManager->getRepository(Currency::class)->findAll();
    $rates = [];
    foreach ($currencies as $currency) {
      $parsedRate = $parsedRates[$currency->getCode()];
      if ($parsedRate && $rate = $this->createRate($parsedRate, $currency, $date)) {
        $rates[$currency->getCode()] = $rate;
        $this->entityManager->persist($rate);
      }
    }

    return $rates;
  }

  /**
   * @param Currency $currency
   * @param DateTime $date
   * @return bool
   */
  private function rateExists(Currency $currency, DateTime $date)
  {
    return boolval($this->entityManager->getRepository(Rate::class)->get($currency, $date));
  }

  /**
   * @param float    $parsedRate
   * @param Currency $currency
   * @param DateTime $date
   * @return Rate|null
   */
  private function createRate($parsedRate, Currency $currency, DateTime $date)
  {
    if (isset($parsedRates[$currency->getCode()]) || $this->rateExists($currency, $date)) {
      return null;
    }

    $rate = new Rate();
    $rate->setCurrency($currency);
    $rate->setValue(floatval($parsedRate));
    $rate->setDate($date);

    return $rate;
  }
}
