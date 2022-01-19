<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * User Class
 * =======================================================================.
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;
    /**
     * User Name.
     * @ORM\Column(type="string")
     */
    private string $username;
    /**
     * User Name.
     * @ORM\Column(type="string")
     */
    private string $password;
    /**
     * Created At.
     *@ORM\Column(type="datetime",name="created_at")
     * @var \DateTimeInterface
     */
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * Get iD.
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get user Name.
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * Set user Name.
     *
     * @return  self
     */
    public function setUsername($username) : self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get user Name.
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Set user Name.
     *
     * @return  self
     */
    public function setPassword($password) : self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get *@ORM\column(type='datetime',name="created_at")
     *
     * @return  \DateTimeInterface
     */
    public function getCreatedAt() : \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set *@ORM\column(type='datetime',name="created_at")
     *
     * @param  \DateTimeInterface  $createdAt  *@ORM\column(type='datetime',name="created_at")
     *
     * @return  self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}