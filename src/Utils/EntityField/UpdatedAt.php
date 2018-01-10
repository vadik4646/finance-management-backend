<?php

namespace App\Utils\EntityField;

use App\Annotation\Fetcher;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAt
{
  /**
   * @var \DateTime
   * @ORM\Column(type="datetime", nullable=true)
   * @Fetcher()
   */
  private $updatedAt = null;

  /**
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * @param \DateTime $updatedAt
   */
  public function setUpdatedAt(\DateTime $updatedAt)
  {
    $this->updatedAt = $updatedAt;
  }

  /**
   * @ORM\PreUpdate
   */
  public function setUpdatedAtValue()
  {
    $this->createdAt = new \DateTime();
  }
}
