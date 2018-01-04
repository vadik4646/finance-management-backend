<?php

namespace App\Service\Session\Bag;

class BagType
{
  const TYPE_ATTRIBUTE = 'attribute';
  const TYPE_METADATA = 'metadata';
  const TYPE_FLASH = 'flash';

  const TYPES_MAP = [
    self::TYPE_ATTRIBUTE,
    self::TYPE_METADATA,
    self::TYPE_FLASH
  ];

  private function __construct() {}
}
