<?php

namespace App\Utils;

use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;

class Searcher
{
  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * @var RepositoryManagerInterface
   */
  private $elasticManager;

  public function __construct(EntityManagerInterface $entityManager, RepositoryManagerInterface $elasticManager)
  {
    $this->entityManager = $entityManager;
    $this->elasticManager = $elasticManager;
  }

  /**
   * @param User   $user
   * @param string $search
   * @return Expense[]
   */
  public function searchExpense(User $user, $search)
  {
    try {
      $query = ElasticQueryFactory::buildExpenseSearchQuery($user->getId(), $search);
      $expenses = $this->elasticManager->getRepository(Expense::class)->find($query);
    } catch (Exception $exception) {
      $expenses = $this->entityManager->getRepository(Expense::class)->searchByUser($user, $search);
    }

    return $expenses;
  }

  /**
   * @param User   $user
   * @param string $search
   * @return Income[]
   */
  public function searchIncome(User $user, $search)
  {
    try {
      $query = ElasticQueryFactory::buildIncomeSearchQuery($user->getId(), $search);
      $incomes = $this->elasticManager->getRepository(Income::class)->find($query);
    } catch (Exception $exception) {
      $incomes = $this->entityManager->getRepository(Income::class)->searchByUser($user, $search);
    }

    return $incomes;
  }
}
