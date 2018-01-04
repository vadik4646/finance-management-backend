<?php

namespace App\Service\BankReport\ExchangeRate;

use DateTime;

class Parser
{
  private $urlTemplate = 'https://bnm.md/en/official_exchange_rates?get_xml=1&date=%s';

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
        $ratesXml = file_get_contents($rateUrl);
        $result = $this->parseXmlRate($ratesXml);
      } catch (\Exception $exception) {}
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

    $currencies = [];
    $rates = simplexml_load_string($ratesXml);
    foreach ($rates->children() as $currency) {
      $currencies[(string)$currency->CharCode] = floatval($currency->Value);
    }

    return $currencies;
  }

}
