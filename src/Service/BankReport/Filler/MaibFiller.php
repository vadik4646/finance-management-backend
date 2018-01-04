<?php

namespace App\Service\BankReport\Filler;

use App\Service\BankReport\ParsedResult;
use App\Service\BankReport\ParsedResultItem;
use App\Service\Entity\Currency;
use App\Service\Filler\Guesser\MaibGuesser;
use Doctrine\ORM\EntityManager;

class MaibFiller
{
  private $entityManger;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManger = $entityManager;
  }

  /**
   * @param ParsedResult $financialReport
   */
  public function fill(ParsedResult $financialReport)
  {
    $this->handleGuesser($financialReport);
    $this->fillAmount($financialReport);
  }

  /**
   * @param ParsedResult $financialReport
   */
  private function handleGuesser(ParsedResult $financialReport)
  {
    $guesser = new MaibGuesser();
    foreach ($financialReport->getResults() as $result) {
      $guesser->handle($result);
    }
  }

  /**
   * @param ParsedResult $financialReport
   */
  private function fillAmount(ParsedResult $financialReport)
  {
    /**
     * @var ParsedResultItem[] $groupedResults
     * @var Currency           $currency
     */
    $currencyRepository = $this->entityManger->getRepository('App\Service\Entity\Currency');

    foreach ($financialReport->getGroupedResults() as $currencyCode => $groupedResults) {
      $currency = $currencyRepository->find($currencyCode);
      foreach ($groupedResults as $result) {
        $result->setAmount($this->getAmount($result, $currency));
      }
    }
  }

  /**
   * @param ParsedResultItem $result
   * @param Currency|null    $currency
   * @return float
   */
  private function getAmount(ParsedResultItem $result, $currency)
  {
    $rateRepository = $this->entityManger->getRepository('App\Service\Entity\Rate');
    if ($result->getCurrencyCode() === 'MDL') {
      return floatval($result->getCurrencyAmount());
    } else {
      $rateSnapshot = $rateRepository->findOneBy(['currency' => $currency, 'date' => $result->getDateTime()]);
      if ($rateSnapshot) {
        return floatval($rateSnapshot->getValue() * $result->getCurrencyAmount());
      }
    }

    return null;
  }
}
