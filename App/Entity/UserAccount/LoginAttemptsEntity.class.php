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
    private DateTimeInterface $created_at;
    /** @var DateTimeInterface */
    private DateTimeInterface $updated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
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

    /**
     * Get the value of created_at.
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at.
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at.
     *
     * @return  self
     */
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}