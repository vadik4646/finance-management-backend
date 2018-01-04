<?php

namespace App\Service;

use App\Annotation\Fetcher;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;

class ResultFetcher
{
  /** @var EntityManagerInterface */
  private $entityManager;

  /** @var Reader */
  private $annotationReader;

  public function __construct(EntityManagerInterface $entityManager, Reader $annotationReader)
  {
    $this->entityManager = $entityManager;
    $this->annotationReader = $annotationReader;
  }

  /**
   * @param mixed $result
   * @return array
   */
  public function toArray($result)
  {
    return $this->fetch($result);
  }

  /**
   * @param mixed $result
   * @return array
   */
  private function fetch($result)
  {
    if (is_array($result) || $result instanceof ArrayCollection) {
      $rows = [];
      foreach ($result as $row) {
        $rows[] = $this->fetch($row);
      }

      return $rows;
    }

    if (is_object($result)) {
      return $this->fetchRow($result);
    }

    return $result;
  }

  /**
   * @param object $entity
   * @return array
   */
  private function fetchRow($entity)
  {
    $fetchedFields = $this->getAssocFieldNames(get_class($entity));
    $fields = [];
    foreach ($fetchedFields as $fetchedField) {
      $fieldValue = $entity->{$fetchedField->getter}();
      if ($fieldValue instanceof \DateTime) {
        $fields[$fetchedField->name] = $this->fetchDateTime($fieldValue);
      } else {
        $fields[$fetchedField->name] = $this->fetch($fieldValue);
      }
    }

    return $fields;
  }

  /**
   * @param \DateTime $dateTime
   * @return string
   */
  private function fetchDateTime(\DateTime $dateTime)
  {
    return $dateTime->format("Y-m-d H:i:s");
  }

  /**
   * @param string $class
   * @return Fetcher[]
   */
  private function getAssocFieldNames($class)
  { // todo move to parts and cache
    $realClass = ClassUtils::getRealClass($class);
    $reflectionClass = new \ReflectionClass($realClass);
    $entityFields = [];
    foreach ($reflectionClass->getProperties() as $reflectionProperty) {
      $reflectionProp = new \ReflectionProperty($realClass, $reflectionProperty->getName());
      $entityField = $this->annotationReader->getPropertyAnnotation($reflectionProp, Fetcher::class);
      if ($entityField) {
        if (!$entityField->name) {
          $entityField->name = $reflectionProp->name;
        }

        if (!$entityField->getter) {
          $entityField->getter = 'get' . ucfirst($reflectionProp->name);
        }
        $entityFields[] = $entityField;
      }
    }

    return $entityFields;
  }
}
