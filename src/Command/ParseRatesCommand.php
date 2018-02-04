<?php

namespace App\Command;

use App\Service\BankReport\ExchangeRateProvider;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParseRatesCommand extends Command
{
  protected static $defaultName = 'app:parse-rates';

  /**
   * @var ExchangeRateProvider
   */
  private $exchangeRateProvider;

  public function __construct(ExchangeRateProvider $exchangeRateProvider)
  {
    parent::__construct();
    $this->exchangeRateProvider = $exchangeRateProvider;
  }

  protected function configure()
  {
    $this
      ->setDescription('Parse and fill currency rates for a date')
      ->addArgument('date', InputArgument::OPTIONAL, 'Unnecessary date');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new SymfonyStyle($input, $output);
    if ($dateString = $input->getArgument('date')) {
      $date = DateTime::createFromFormat('Y-m-d', $dateString);
    } else {
      $date = new DateTime('now');
    }

    $rates = $this->exchangeRateProvider->getAndRegister($date);
    foreach ($rates as $rate) {
      $io->success('Currency ' . $rate->getCurrency()->getCode() . ' has been imported');
    }
  }
}
