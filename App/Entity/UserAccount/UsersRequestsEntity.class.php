<?php

declare(strict_types=1);

class UsersRequestsEntity extends Entity
{
    /** @id */
    private int $urID;
    private int $userID;
    private string $hash;
    private int $type;
    private string $timestamp;
    /** @var DateTimeInterface */
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of urID.
     */
    public function getUrID()
    {
        return $this->urID;
    }

    /**
     * Get the value of userID.
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Set the value of userID.
     *
     * @return  self
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;

        return $this;
    }

    /**
     * Get the value of hash.
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the value of hash.
     *
     * @return  self
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get the value of type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of timestamp.
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set the value of timestamp.
     *
     * @return  self
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Set the value of createdAt.
     *
     * @return  self
     */
    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     *
     * @return  self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }
}