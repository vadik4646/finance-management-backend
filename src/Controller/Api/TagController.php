<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller
{
  /**
   * @Route("tag", methods={"GET"}, name="tags")
   */
  public function tags(JsonRequest $request, ApiResponse $apiResponse, TagRepository $tagRepository, ResultFetcher $resultFetcher)
  {
    $tags = $tagRepository->getUsersAndPublic($this->getUser(), $request->get('search'));

    return $apiResponse->appendData($resultFetcher->toArray($tags))->send();
  }
}
