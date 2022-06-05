<?php

declare(strict_types=1);

class LoginAttemptsEntity extends Entity
{
    /** @id */
    private int $laID;
    /** @UserID */
    private int $userID;
    private string $timestamp;
    private string $ip;
    /** @var DateTimeInterface */
    private DateTimeInterface $createdAt;
    /** @var DateTimeInterface */
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of laID.
     */
    public function getLaID()
    {
        return $this->laID;
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
     * Get the value of ip.
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of ip.
     *
     * @return  self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    public function delete(?string $field = null) : self
    {
        unset($this->$field);
        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
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
}