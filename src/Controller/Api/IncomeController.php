<?php

namespace App\Controller\Api;

use App\Entity\Income;
use App\Entity\Tag;
use App\Form\IncomeType;
use App\Repository\IncomeRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use App\Utils\Searcher;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IncomeController extends Controller
{
  /**
   * @Route("/income", name="user_incomes", methods={"GET"})
   */
  public function incomes(Searcher $searcher, JsonRequest $request, ApiResponse $apiResponse, ResultFetcher $resultFetcher)
  {
    $incomes = $searcher->searchIncome($this->getUser(), $request->get('search'));

    return $apiResponse->appendData($resultFetcher->toArray($incomes))->send();
  }

  /**
   * @Route("/income/{id}", name="user_income", methods={"GET"})
   */
  public function details($id, ApiResponse $apiResponse, IncomeRepository $incomeRepository, ResultFetcher $resultFetcher)
  {
    $income = $incomeRepository->find($id);

    if ($income && $this->getUser()->isEqualTo($income->getUser())) {
      return $apiResponse->appendData($resultFetcher->toArray($income))->send();
    }

    return $apiResponse->setMessage('Income is not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
  }

  /**
   * @Route("/income/create", name="create_income", methods={"POST"})
   */
  public function create(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $income = new Income();
    $form = $this->createAndHandleForm($income, $request->all(), $entityManager);

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($income);
    $entityManager->flush();

    return $apiResponse->setMessage('Income has been created')->send();
  }

  /**
   * @Route("/income/import", name="import_income", methods={"POST"})
   */
  public function import(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    foreach ($request->get('incomes') as $inputExpense) {
      $income = new Income();
      $this->createAndHandleForm($income, $inputExpense, $entityManager);
      $entityManager->persist($income);
    }

    $entityManager->flush();

    return $apiResponse->setMessage('Incomes have been imported')->send();
  }

  /**
   * @Route("/income/edit/{id}", name="edit_income", methods={"POST"})
   */
  public function edit($id, JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $income = $entityManager->getRepository(Income::class)->find($id);

    if (!$income || !$this->getUser()->isEqualTo($income->getUser())) {
      return $apiResponse->setMessage('Income is not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
    }

    $form = $this->createAndHandleForm($income, $request->all(), $entityManager);
    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($income);
    $entityManager->flush();

    return $apiResponse->setMessage('Income has been updated')->send();
  }

  /**
   * @Route("/income/delete", name="delete_income", methods={"POST"})
   */
  public function delete(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $income = $entityManager->getRepository(Income::class)->find($request->get('id'));

    if (!$income || !$this->getUser()->isEqualTo($income->getUser())) {
      return $apiResponse->setMessage('Income is not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
    }

    $entityManager->remove($income);
    $entityManager->flush();

    return $apiResponse->setMessage('Income has been deleted')->send();
  }

  private function getTagIdMap($rawTags, EntityManagerInterface $entityManager)
  {
    if (empty($rawTags)) {
      return [];
    }

    $tags = $entityManager->getRepository(Tag::class)->createOrGetExisting((array)$rawTags, $this->getUser());

    $tagIdMap = [];
    foreach ($tags as $tag) {
      $tagIdMap[] = $tag->getId();
    }

    return $tagIdMap;
  }

  private function createAndHandleForm(Income $income, $input, EntityManagerInterface $entityManager)
  {
    $tagsIdMap = $this->getTagIdMap(isset($input['tags']) ? $input['tags'] : [], $entityManager);
    $input['tags'] = $tagsIdMap;
    $input['user'] = $this->getUser()->getId();

    $form = $this->createForm(IncomeType::class, $income);
    $form->submit($input);

    return $form;
  }
}
