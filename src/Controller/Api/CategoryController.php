<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
  /**
   * @Route("category", methods={"GET"}, name="categories")
   */
  public function categories(JsonRequest $request, ApiResponse $apiResponse, CategoryRepository $categoryRepository, ResultFetcher $resultFetcher)
  {
    $categories = $categoryRepository->get($request->get('search'), $this->getUser());

    return $apiResponse->appendData($resultFetcher->toArray($categories))->send();
  }

  /**
   * @Route("category/create", methods={"POST"}, name="category_create")
   */
  public function create(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $request->set('user', $this->getUser()->getId());
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($category);
    $entityManager->flush();

    return $apiResponse->setMessage('Category has been created')->send();
  }

  /**
   * @Route("category/edit/{id}", methods={"POST"}, name="category_update")
   */
  public function edit($id, JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $category = $entityManager->getRepository(Category::class)->find($id);

    if (!$category || !$this->getUser()->isEqualTo($category->getUser())) {
      return $apiResponse->setMessage('Category is not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
    }

    $form = $this->createForm(CategoryType::class, $category);
    $request->set('user', $this->getUser()->getId());
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($category);
    $entityManager->flush();

    return $apiResponse->setMessage('Category has been updated')->send();
  }

  /**
   * @Route("category/delete", name="category_delete", methods={"POST"})
   */
  public function delete(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $category = $entityManager->getRepository(Category::class)->find($request->get('id'));

    if (!$category || !$this->getUser()->isEqualTo($category->getUser())) {
      return $apiResponse->setMessage('Category is not found')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
    }

    $entityManager->remove($category);
    $entityManager->flush();

    return $apiResponse->setMessage('Category has been deleted')->send();
  }
}
