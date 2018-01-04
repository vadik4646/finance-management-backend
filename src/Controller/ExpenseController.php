<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Expense;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExpenseController extends Controller
{
  /**
   * @Route("/expense", name="user_expenses", methods={"GET"})
   */
  public function list(ApiResponse $apiResponse, ExpenseRepository $expenseRepository, ResultFetcher $resultFetcher)
  {
    $expenses = $expenseRepository->findByUser($this->getUser());

    return $apiResponse->appendData($resultFetcher->toArray($expenses))->send();
  }

  /**
   * @Route("/expense/{id}", name="user_expense")
   */
  public function details($id, ApiResponse $apiResponse, ExpenseRepository $expenseRepository, ResultFetcher $resultFetcher)
  {
    $expense = $expenseRepository->find($id);

    if ($expense && $this->getUser()->isEqualTo($expense->getUser())) {
      return $apiResponse->appendData($resultFetcher->toArray($expense))->send();
    }

    return $apiResponse->setMessage('Expense not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
  }

  /**
   * @Route("/expense/{id}", name="user_expense")
   */
  public function create(JsonRequest $request, CategoryRepository $categoryRepository, ApiResponse $apiResponse)
  {
    $expense = new Expense();



  }
}
