<?php

namespace App\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Fetcher
{
  /** @var string */
  public $name;

  /** @var mixed */
  public $getter = null;
}
