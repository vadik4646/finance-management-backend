<?php

namespace App\Service\BankReport\Filler;

use App\Service\BankReport\ParsedResult;
use App\Service\BankReport\Filler\Guesser\VictoriaBankGuesser;
use Doctrine\ORM\EntityManagerInterface;

class VictoriaBankFiller
{
  private $entityManger;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManger = $entityManager;
  }

  /**
   * @param ParsedResult $financialReport
   */
  public function fill(ParsedResult $financialReport)
  {
    $this->handleGuesser($financialReport);
  }

  /**
   * @param ParsedResult $financialReport
   */
  private function handleGuesser(ParsedResult $financialReport)
  {
    $guesser = new VictoriaBankGuesser();
    foreach ($financialReport->getResults() as $result) {
      $guesser->handle($result);
    }
  }
}
