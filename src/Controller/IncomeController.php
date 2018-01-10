<?php

namespace App\Controller;

use App\Entity\Income;
use App\Entity\Tag;
use App\Form\IncomeType;
use App\Repository\IncomeRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IncomeController extends Controller
{
  /**
   * @Route("/income", name="user_incomes", methods={"GET"})
   */
  public function list(ApiResponse $apiResponse, IncomeRepository $incomeRepository, ResultFetcher $resultFetcher)
  {
    $incomes = $incomeRepository->findByUser($this->getUser());

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

    return $apiResponse->setMessage('Income not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
  }

  /**
   * @Route("/income/create", name="create_income", methods={"POST"})
   */
  public function create(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $tagsIdMap = $this->getTagIdMap($request->get('tags'), $entityManager);
    $request->set('tags', $tagsIdMap);

    $income = new Income();
    $form = $this->createForm(IncomeType::class, $income);
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($income);
    $entityManager->flush();

    return $apiResponse->setMessage('Income created')->send();
  }

  /**
   * @Route("/income/edit/{id}", name="edit_income", methods={"POST"})
   */
  public function edit($id, JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $expense = $entityManager->getRepository(Income::class)->find($id);

    $tagsIdMap = $this->getTagIdMap($request->get('tags'), $entityManager);
    $request->set('tags', $tagsIdMap); // todo creator

    $form = $this->createForm(IncomeType::class, $expense);
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($expense);
    $entityManager->flush();

    return $apiResponse->setMessage('Income updated')->send();
  }

  /**
   * @Route("/income/delete", name="delete_income", methods={"POST"})
   */
  public function delete(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $expense = $entityManager->getRepository(Income::class)->find($request->get('id'));

    $entityManager->remove($expense);
    $entityManager->flush();

    return $apiResponse->setMessage('Income deleted')->send();
  }

  private function getTagIdMap($rawTags, EntityManagerInterface $entityManager)
  {
    $tags = $entityManager->getRepository(Tag::class)->createOrGetExisting($rawTags, $this->getUser());

    $tagIdMap = [];
    foreach ($tags as $tag) {
      $tagIdMap[] = $tag->getId();
    }

    return $tagIdMap;
  }
}
