<?php

namespace App\Service\BankReport\Filler;

use App\Service\BankReport\BankFactory;
use Doctrine\ORM\EntityManagerInterface;

class FillerFactory
{
  private $entityManger;

  private static $fillerMap = [
    BankFactory::MOLDOVA_AGROIND_BANK => MaibFiller::class,
    BankFactory::VICTORIA_BANK        => VictoriaBankFiller::class
  ];

  public function __construct(EntityManagerInterface $entityManger)
  {
    $this->entityManger = $entityManger;
  }

  /**
   * @param string $filler
   * @return null|FillerInterface
   */
  public function get($filler)
  {
    return array_key_exists($filler, self::$fillerMap) ? new self::$fillerMap[$filler]($this->entityManger) : null;
  }
}
