<?php

namespace App\Service;

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
    if (isset($this->jsonContent[$key])) {
      return $this->jsonContent[$key];
    }

    if ($this->query->has($key)) {
      return $this->query->get($key);
    }

    return $default;
  }

  /**
   * @param string $key
   * @param mixed  $value
   * @return mixed|null
   */
  public function set($key, $value)
  {
    return $this->jsonContent[$key] = $value;
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  public function has($key)
  {
    return isset($key, $this->jsonContent) || $this->query->has($key);
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
}
