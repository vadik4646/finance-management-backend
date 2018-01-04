<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
  /**
   * @Route("/login", name="login", methods={"POST"})
   * @param JsonRequest                  $request
   * @param UserRepository               $userRepository
   * @param UserPasswordEncoderInterface $passwordEncoder
   * @param ApiResponse                  $response
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function loginAction(
    JsonRequest $request,
    UserRepository $userRepository,
    UserPasswordEncoderInterface $passwordEncoder,
    ApiResponse $response
  ) {
    $user = $userRepository->loadUserByEmail($request->get('email'));

    if (!$user || !$passwordEncoder->isPasswordValid($user, $request->get('password'))) {
      return $response->setMessage('Error')->setCode(ApiResponse::HTTP_BAD_REQUEST)->send();
    }

    $this->get('app.security.authentication_manager')->authenticate($user);

    return $response->setMessage('logged in')->send();
  }

  /**
   * @Route("/register", name="register", methods={"POST"})
   * @param JsonRequest                  $request
   * @param UserPasswordEncoderInterface $passwordEncoder
   * @param ApiResponse                  $response
   * @return Response
   */
  public function registerAction(JsonRequest $request, UserPasswordEncoderInterface $passwordEncoder, ApiResponse $response) {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->submit($request->all());

    if (!$form->isValid()) {
      return $response->setValidationErrors($form)->send();
    }

    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
    $user->setPassword($password);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($user);
    $entityManager->flush();
    $request->getSession()->getMetadataBag()->user = $user;

    return $response->setMessage('Logged in')->send();
  }
}
