<?php

namespace App\Service\BankReport;

use App\Entity\Currency;
use App\Service\CurrencyConverter\CurrencyConverterFactory;
use Doctrine\ORM\EntityManagerInterface;

class Parser
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var CurrencyConverterFactory
   */
  private $currencyConverterFactory;

  /**
   * @var CurrencyConverterFactory
   */
  private $primaryCurrency;

  public function __construct(EntityManagerInterface $entityManager, CurrencyConverterFactory $currencyConverterFactory)
  {
    $this->entityManager = $entityManager;
    $this->currencyConverterFactory = $currencyConverterFactory;
    $this->primaryCurrency = $this->entityManager->getRepository(Currency::class)->find('MDL'); // todo move to customization
  }

  /**
   * @param string $fileName
   * @param string $bank
   * @return ParsedResult|null
   */
  public function parse($fileName, $bank)
  {
    if (!BankFactory::exists($bank)) {
      return null;
    }

    $parser = BankFactory::get($bank);
    $parsedResult = $parser->parse($fileName);
    if ($parsedResult) {
      return $this->fillCosts($parsedResult);
    }

    return null;
  }

  /**
   * @param ParsedResult $parsedResults
   * @return ParsedResult
   */
  private function fillCosts(ParsedResult $parsedResults)
  {
    foreach ($parsedResults->getGroupedResults() as $currencyCode => $result) {
      if ($currency = $this->entityManager->getRepository(Currency::class)->find($currencyCode)) {
        $this->fillResultsByCurrency($result, $currency);
      }
    }

    return $parsedResults;
  }

  /**
   * @param ParsedResultItem[] $parsedResults
   * @param Currency           $currency
   */
  private function fillResultsByCurrency($parsedResults, Currency $currency)
  {
    foreach ($parsedResults as $parsedResult) {
      if (is_null($parsedResult->getCurrencyAmount()) || is_null($parsedResult->getAmount())) {
        $this->fill($parsedResult, $currency);
      }
    }
  }

  /**
   * @param ParsedResultItem $parsedResultItem
   * @param Currency         $currency
   */
  private function fill(ParsedResultItem $parsedResultItem, Currency $currency)
  {
    if (!$parsedResultItem->getCurrencyCode() || !$parsedResultItem->getDateTime()) {
      return;
    }

    if ($currency->getCode() === $this->primaryCurrency->getCode()) {
      if (!$parsedResultItem->getAmount()) {
        $parsedResultItem->setAmount($parsedResultItem->getCurrencyAmount());
      }

      if (!$parsedResultItem->getCurrencyAmount()) {
        $parsedResultItem->setCurrencyAmount($parsedResultItem->getAmount());
      }
    }

    if (is_null($parsedResultItem->getAmount())) {
      $currencyConverter = $this->currencyConverterFactory->build($currency, $parsedResultItem->getDateTime());
      $amount = $currencyConverter->to($this->primaryCurrency, $parsedResultItem->getCurrencyAmount());
      $parsedResultItem->setAmount($amount);
    }

    if (is_null($parsedResultItem->getCurrencyAmount())) {
      $currencyConverter = $this->currencyConverterFactory->build($this->primaryCurrency, $parsedResultItem->getDateTime());
      $amount = $currencyConverter->to($currency, $parsedResultItem->getCurrencyAmount());
      $parsedResultItem->setCurrencyAmount($amount);
    }
  }
}
