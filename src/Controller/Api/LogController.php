<?php

namespace App\Controller\Api;

use App\Entity\Log;
use App\Form\LogType;
use App\Service\ApiResponse;
use App\Service\JsonRequest;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LogController extends Controller
{
  /**
   * @Route("log/append", methods={"post"}, name="append_log")
   */
  public function append(JsonRequest $request, ApiResponse $apiResponse)
  {
    if (!isset(Logger::getLevels()[$request->get('type')])) {
      return $apiResponse->setMessage('Invalid type')->setCode(ApiResponse::HTTP_NOT_FOUND)->send();
    }

    $this->get('app.logger')->log(
      $request->get('message'),
      $request->get('type'),
      \App\Utils\Type\LogType::FRONT_END,
      $request->get('params', [])
    );

    return $apiResponse->setMessage('Log has been appended')->send();
  }
}
