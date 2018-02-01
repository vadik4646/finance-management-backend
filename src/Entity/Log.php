<?php

namespace App\Entity;

use App\Utils\EntityField\CreatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="LogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Log
{
  use CreatedAt;

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank()
   */
  private $type;

  /**
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank()
   */
  private $source;

  /**
   * @ORM\Column(type="text")
   * @Assert\NotBlank()
   */
  private $message;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $params;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(nullable=true)
   */
  private $user = null;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type): void
  {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setMessage($message): void
  {
    $this->message = $message;
  }

  /**
   * @return mixed
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * @param mixed $params
   */
  public function setParams($params): void
  {
    $this->params = $params;
  }

  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param mixed $user
   */
  public function setUser($user): void
  {
    $this->user = $user;
  }

  /**
   * @return string
   */
  public function getSource()
  {
    return $this->source;
  }

  /**
   * @param string $source
   */
  public function setSource($source): void
  {
    $this->source = $source;
  }
}
