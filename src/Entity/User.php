<?php

namespace App\Entity;

use App\Annotation\Fetcher;
use App\Utils\EntityField\CreatedAt;
use App\Utils\EntityField\UpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable, EquatableInterface
{
  use CreatedAt;
  use UpdatedAt;

  const STATUS_ACTIVE = 1;

  /**
   * @var int
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Fetcher()
   */
  private $id;

  /**
   * @var string
   * @ORM\Column(type="string", length=250, unique=true)
   * @Assert\NotBlank()
   * @Assert\Email()
   * @Fetcher()
   */
  private $email;

  /**
   * @var string
   * @ORM\Column(type="string", length=250)
   */
  private $password;

  /**
   * @var int
   * @ORM\Column(type="integer", length=2)
   */
  private $status = self::STATUS_ACTIVE;

  /**
   * @var string
   * @Assert\NotBlank()
   */
  private $plainPassword;

  /**
   * @var \App\Service\Session\Session[]
   *
   * @ORM\OneToMany(targetEntity="App\Entity\Session", mappedBy="user")
   */
  private $sessions;

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id)
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   */
  public function setEmail(string $email)
  {
    $this->email = $email;
  }

  /**
   * @return string
   */
  public function getPlainPassword()
  {
    return $this->plainPassword;
  }

  /**
   * @param string $plainPassword
   */
  public function setPlainPassword(string $plainPassword)
  {
    $this->plainPassword = $plainPassword;
  }

  /**
   * String representation of object
   *
   * @link  http://php.net/manual/en/serializable.serialize.php
   * @return string the string representation of the object or null
   * @since 5.1.0
   */
  public function serialize()
  {
    return serialize(
      [
        $this->id,
        $this->email,
        $this->password
      ]
    );
  }

  /**
   * Constructs the object
   *
   * @link  http://php.net/manual/en/serializable.unserialize.php
   * @param string $serialized <p>
   *                           The string representation of the object.
   *                           </p>
   * @return void
   * @since 5.1.0
   */
  public function unserialize($serialized)
  {
    list (
      $this->id,
      $this->email,
      $this->password
      ) = unserialize($serialized);
  }

  /**
   * Returns the roles granted to the user.
   *
   * Alternatively, the roles might be stored on a ``roles`` property,
   * and populated in any number of different ways when the user object
   * is created.
   *
   * @return string[] The user roles
   */
  public function getRoles()
  {
    return ['ROLE_USER'];
  }

  /**
   * Returns the password used to authenticate the user.
   *
   * This should be the encoded password. On authentication, a plain-text
   * password will be salted, encoded, and then compared to this value.
   *
   * @return string The password
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param string $password
   */
  public function setPassword($password)
  {
    $this->password = $password;
  }

  /**
   * Returns the salt that was originally used to encode the password.
   *
   * This can return null if the password was not encoded using a salt.
   *
   * @return string|null The salt
   */
  public function getSalt()
  {
    return null;
  }

  /**
   * Returns the username used to authenticate the user.
   *
   * @return string The username
   */
  public function getUsername()
  {
    return $this->email;
  }

  /**
   * Removes sensitive data from the user.
   *
   * This is important if, at any given point, sensitive information like
   * the plain-text password is stored on this object.
   */
  public function eraseCredentials()
  {
    $this->plainPassword = null;
  }

  /**
   * The equality comparison should neither be done by referential equality
   * nor by comparing identities (i.e. getId() === getId()).
   *
   * However, you do not need to compare every attribute, but only those that
   * are relevant for assessing whether re-authentication is required.
   *
   * Also implementation should consider that $user instance may implement
   * the extended user interface `AdvancedUserInterface`.
   *
   * @param UserInterface $user
   * @return bool
   */
  public function isEqualTo(UserInterface $user)
  {
    if (!$user instanceof User) {
      return false;
    }

    if ($this->password !== $user->getPassword()) {
      return false;
    }

    if ($this->email !== $user->getUsername()) {
      return false;
    }

    return true;
  }

  /**
   * @return int
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @param int $status
   */
  public function setStatus(int $status)
  {
    $this->status = $status;
  }

  /**
   * @return \App\Service\Session\Session[]
   */
  public function getSessions(): array
  {
    return $this->sessions;
  }
}
