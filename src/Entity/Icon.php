<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Annotation\Fetcher;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IconRepository")
 */
class Icon
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Fetcher()
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=100)
   * @Fetcher()
   */
  private $title;

  /**
   * @ORM\Column(type="string", length=100)
   * @Fetcher()
   */
  private $class;

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param string $title
   */
  public function setTitle($title): void
  {
    $this->title = $title;
  }

  /**
   * @return string
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * @param string $class
   */
  public function setClass($class): void
  {
    $this->class = $class;
  }
}
