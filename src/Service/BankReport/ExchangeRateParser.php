<?php

namespace App\Service\BankReport;

use DateTime;
use Psr\Log\LoggerInterface;

class ExchangeRateParser
{
  private $urlTemplate = 'https://bnm.md/en/official_exchange_rates?get_xml=1&date=%s';

  /**
   * @var LoggerInterface
   */
  private $logger;

  public function __construct(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  /**
   * @param DateTime $date
   * @return array|null
   */
  public function get(DateTime $date)
  {
    return $this->getBnmRate($date);
  }

  /**
   * @param DateTime $date
   * @return array|null
   */
  private function getBnmRate(DateTime $date)
  {
    $result = null;
    $nrTries = 0;

    while ($result === null && ++$nrTries < 4) {
      try {
        $rateUrl = sprintf($this->urlTemplate, $date->format('d.m.Y'));
        $result = $this->parseXmlRate(file_get_contents($rateUrl));
      } catch (\Exception $exception) {
        $this->logger->error(sprintf("Can't parse BNM rate. Url %s, Exception: %s", $rateUrl, $exception->getMessage()));
      }
    }

    return $result;
  }

  /**
   * @param $ratesXml
   * @return array|null
   */
  private function parseXmlRate($ratesXml)
  {
    if (!$ratesXml) {
      return null;
    }

    $currencies = ['MDL' => 1];
    $rates = simplexml_load_string($ratesXml);
    foreach ($rates->children() as $currency) {
      $currencies[(string)$currency->CharCode] = floatval($currency->Value);
    }

    return $currencies;
  }

}
