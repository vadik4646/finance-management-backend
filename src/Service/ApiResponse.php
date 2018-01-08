<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{
  const HTTP_OK          = 200;
  const HTTP_NOT_FOUND   = 404;
  const HTTP_BAD_REQUEST = 400;

  private $code = self::HTTP_OK;
  private $message = '';
  private $data = [];
  private $validationErrors = [];

  /**
   * @param int $code
   * @return ApiResponse
   */
  public function setCode($code)
  {
    $this->code = $code;

    return $this;
  }

  /**
   * @param string $message
   * @return ApiResponse
   */
  public function setMessage($message)
  {
    $this->message = $message;

    return $this;
  }

  /**
   * @param $data
   * @return ApiResponse
   */
  public function appendData($data)
  {
    $this->data = array_merge($this->data, $data);

    return $this;
  }

  /**
   * @return JsonResponse
   */
  public function send()
  {
    $response = [];

    if ($this->message) {
      $response['message'] = $this->message;
    }

    if ($this->data) {
      $response['data'] = $this->data;
    }

    if ($this->validationErrors) {
      $response['validationErrors'] = $this->validationErrors;
    }

    return new JsonResponse(
      $response,
      $this->code
    );
  }

  /**
   * @param FormInterface $form
   * @return $this
   */
  public function setValidationErrors(FormInterface $form)
  {
    $this->validationErrors = $this->getErrorMessages($form);

    return $this;
  }

  /**
   * @param FormInterface $form
   * @return array
   */
  private function getErrorMessages(FormInterface $form)
  {
    $errors = [];

    foreach ($form->getErrors() as $key => $error) {
      if ($form->isRoot()) {
        $errors['root'][] = $error->getMessage();
      } else {
        $errors[] = $error->getMessage();
      }
    }

    foreach ($form->all() as $child) {
      if ($child->isSubmitted() && !$child->isValid()) {
        $errors[$child->getName()] = $this->getErrorMessages($child);
      }
    }

    return $errors;
  }
}
