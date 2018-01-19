<?php

namespace App\Controller\Api;

use App\Entity\Customization;
use App\Form\CustomizationType;
use App\Repository\CustomizationRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use App\Service\ResultFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomizationController extends Controller
{
  /**
   * @Route("customization", methods={"GET"}, name="customization")
   */
  public function customization(
    ApiResponse $apiResponse,
    CustomizationRepository $customizationRepository,
    ResultFetcher $resultFetcher
  ) {
    $customizations = $customizationRepository->findByUser($this->getUser());

    return $apiResponse->appendData($resultFetcher->toArray($customizations))->send();
  }

  /**
   * @Route("customization/set", methods={"POST"}, name="set_customization")
   */
  public function set(JsonRequest $request, ApiResponse $apiResponse, EntityManagerInterface $entityManager)
  {
    $customization = $entityManager->getRepository(Customization::class)->findOrCreate(
      $this->getUser(),
      $request->get('name')
    );
    $request->set('user', $this->getUser()->getId());
    $form = $this->createForm(CustomizationType::class, $customization);
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $apiResponse->setValidationErrors($form)->send();
    }

    $entityManager->persist($customization);
    $entityManager->flush();

    return $apiResponse->setMessage('Customization has been configured')->send();
  }
}
