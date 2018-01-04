<?php

namespace App\Service;

use App\Service\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class JsonRequest extends Request
{
  private $jsonContent = [];

  public function __construct(
    array $query = [],
    array $request = [],
    array $attributes = [],
    array $cookies = [],
    array $files = [],
    array $server = [],
    $content = null
  ) {
    parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
  }

  /**
   * @param string $key
   * @param mixed  $default
   * @return mixed|null
   */
  public function get($key, $default = null)
  {
    if (array_key_exists($key, $this->jsonContent)) {
      return $this->jsonContent[$key];
    }

    if ($this->query->has($key)) {
      return $this->query->get($key);
    }

    return $default;
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  public function has($key)
  {
    return array_key_exists($key, $this->jsonContent) || $this->query->has($key);
  }

  /**
   * @param array $jsonContent
   */
  public function setContent($jsonContent)
  {
    $this->jsonContent = (array)$jsonContent;
  }

  /**
   * @return array
   */
  public function all()
  {
    return array_merge($this->query->all(), $this->jsonContent);
  }

  /**
   * @return null|Session
   */
  public function getSession()
  {
    return parent::getSession();
  }
}
