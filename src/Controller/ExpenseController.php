<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Expense;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\ExpenseType;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Doctrine\ORM\EntityManagerInterface;
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
   * @Route("/expense/{id}", name="user_expense", methods={"GET"})
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
   * @Route("/expense/create", name="create_expense", methods={"POST"})
   */
  public function create(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $tagsIdMap = $this->createNewTags($request->get('tags'), $entityManager);
    $request->set('tags', $tagsIdMap);

    $expense = new Expense();
    $form = $this->createForm(ExpenseType::class, $expense);
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($expense);
    $entityManager->flush();

    return $apiResponse->setMessage('Expense created')->send();
  }

  private function createNewTags($rawTags, EntityManagerInterface $entityManager)
  {
    $existingIdMap = array_filter($rawTags, 'is_numeric');
    $newTags = array_diff($rawTags, $existingIdMap);
    $user = $this->getUser();
    $user = $user instanceof User ? $user : null;
    $tags = $entityManager->getRepository(Tag::class)->createOrGetExisting($newTags, $user);

    $newIdMap = [];
    foreach ($tags as $tag) {
      $newIdMap[] = $tag->getId();
    }

    return array_merge($existingIdMap, $newIdMap);
  }
}
