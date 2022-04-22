<?php

declare(strict_types=1);

class UserSessionsEntity extends Entity
{
    /** @id */
    private int $usID;
    /** @Remember Me Cookie */
    private string $remember_me_cookie;
    private string $session_token;
    private string $userID;
    private string $user_agent;
    private string $user_cookie;
    private string $email;
    /** @var DateTimeInterface */
    private DateTimeInterface $created_at;
    /** @var DateTimeInterface */
    private DateTimeInterface $udated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    /**
     * Get the value of usID.
     */
    public function getUsID()
    {
        return $this->usID;
    }

    /**
     * Get the value of remember_me_cookie.
     */
    public function getRemember_me_cookie()
    {
        return $this->remember_me_cookie;
    }

    /**
     * Set the value of remember_me_cookie.
     *
     * @return  self
     */
    public function setRemember_me_cookie($remember_me_cookie)
    {
        $this->remember_me_cookie = $remember_me_cookie;

        return $this;
    }

    /**
     * Get the value of session_token.
     */
    public function getSession_token()
    {
        return $this->session_token;
    }

    /**
     * Set the value of session_token.
     *
     * @return  self
     */
    public function setSession_token($session_token)
    {
        $this->session_token = $session_token;

        return $this;
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
     * Get the value of user_agent.
     */
    public function getUser_agent()
    {
        return $this->user_agent;
    }

    /**
     * Set the value of user_agent.
     *
     * @return  self
     */
    public function setUser_agent($user_agent)
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    /**
     * Get the value of user_cookie.
     */
    public function getUser_cookie()
    {
        return $this->user_cookie;
    }

    /**
     * Set the value of user_cookie.
     *
     * @return  self
     */
    public function setUser_cookie($user_cookie)
    {
        $this->user_cookie = $user_cookie;

        return $this;
    }

    /**
     * Get the value of udated_at.
     */
    public function getUdated_at()
    {
        return $this->udated_at;
    }

    /**
     * Set the value of udated_at.
     *
     * @return  self
     */
    public function setUdated_at($udated_at)
    {
        $this->udated_at = $udated_at;

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
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function delete(string $field) : void
    {
        unset($this->$field);
    }

    /**
     * Set the value of usID.
     *
     * @return  self
     */
    public function setUsID($usID)
    {
        $this->usID = $usID;

        return $this;
    }
}