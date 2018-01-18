<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
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
}
