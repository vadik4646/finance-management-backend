<?php

namespace App\Service\BankReport;

class ParsedResultItem
{
  /** @var \DateTime|null */
  private $dateTime = null;

  /** @var string|null */
  private $description = null;

  /** @var null|float */
  private $currencyAmount = null;

  /** @var null|string */
  private $currencyCode = null;

  /** @var null|float */
  private $amount = null;

  /** @var null|string */
  private $type = null;

  /**
   * @return \DateTime|null
   */
  public function getDateTime()
  {
    return $this->dateTime;
  }

  /**
   * @param \DateTime|null $dateTime
   * @return $this
   */
  public function setDateTime($dateTime)
  {
    $this->dateTime = $dateTime;

    return $this;
  }

  /**
   * @return null|string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param null|string $description
   * @return $this
   */
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return float|null
   */
  public function getCurrencyAmount()
  {
    return $this->currencyAmount;
  }

  /**
   * @param string|null $currencyAmount
   * @return $this
   */
  public function setCurrencyAmount($currencyAmount)
  {
    $this->currencyAmount = floatval(preg_replace('/\s/u', '', $currencyAmount));

    return $this;
  }

  /**
   * @return null|string
   */
  public function getCurrencyCode()
  {
    return $this->currencyCode;
  }

  /**
   * @param null|string $currencyCode
   * @return $this
   */
  public function setCurrencyCode($currencyCode)
  {
    $this->currencyCode = $currencyCode;

    return $this;
  }

  /**
   * @return float|null
   */
  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * @param string|null $amount
   * @return $this
   */
  public function setAmount($amount)
  {
    $this->amount = floatval(preg_replace('/\s/u', '', $amount));

    return $this;
  }

  /**
   * @return null|string
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param null|string $type
   * @return $this
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  public function export()
  {
    return [
      'dateTime' => $this->dateTime ? $this->dateTime->format('Y-m-d H:i:s') : null,
      'description' => $this->description,
      'currencyAmount' => $this->currencyAmount,
      'currencyCode' => $this->currencyCode,
      'amount' => $this->amount,
      'type' => $this->type
    ];
  }
}
