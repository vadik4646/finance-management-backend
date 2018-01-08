<?php

namespace App\Utils\EntityField;

use App\Annotation\Fetcher;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAt
{
  /**
   * @var \DateTime
   * @ORM\Column(type="datetime")
   * @Fetcher()
   */
  private $createdAt;

  /**
   * @return \DateTime
   */
  public function getCreatedAt(): \DateTime
  {
    return $this->createdAt;
  }

  /**
   * @param \DateTime $createdAt
   */
  public function setCreatedAt(\DateTime $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  /**
   * @ORM\PrePersist
   */
  public function setCreatedAtValue()
  {
    $this->createdAt = new \DateTime();
  }
}
