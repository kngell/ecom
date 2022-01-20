<?php

declare(strict_types=1);

use DateTimeInterface;

class UsersEntity extends Entity
{
    private int $userID;
    private string $lastName;
    private string $firstName;
    private string $userName;
    /** @var string */
    private string $email;
    private string $password;
    /** @var DateTimeInterface */
    private DateTimeInterface $registerDate;
    /** @var DateTimeInterface */
    private DateTimeInterface $updateAt;
    private string $profileImage;
    private string $salt;
    private string $token;
    private string $user_cookie;
    private string $user_customerID;
    private string $remember_cookie;
    /** @var DateTimeInterface */
    private DateTimeInterface $token_expire;
    private string $phone;
    private int $deleted;
    private int $verified;
    private string $fb_access_token;

    public function __construct()
    {
        $this->registerDate = new DateTimeImmutable();
    }

    /**
     * Get the value of userID.
     */
    public function getUserID() : int
    {
        return $this->userID;
    }

    /**
     * Get the value of lastName.
     */
    public function getLastName() : string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName.
     *
     * @return  self
     */
    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get the value of firstName.
     */
    public function getFirstName() : string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName.
     *
     * @return  self
     */
    public function setFirstName(string $firstName) : self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get the value of userName.
     */
    public function getUserName() : string
    {
        return $this->userName;
    }

    /**
     * Set the value of userName.
     *
     * @return  self
     */
    public function setUserName(string $userName) : self
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return  self
     */
    public function setEmail(string $email) : self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password.
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @return  self
     */
    public function setPassword(string $password) : self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the value of registerDate.
     */
    public function getRegisterDate() : DateTimeInterface
    {
        return $this->registerDate;
    }

    /**
     * Get the value of updateAt.
     */
    public function getUpdateAt() : DateTimeInterface
    {
        return $this->updateAt;
    }

    /**
     * Set the value of updateAt.
     *
     * @return  self
     */
    public function setUpdateAt(DateTimeInterface $updateAt) : self
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * Get the value of profileImage.
     */
    public function getProfileImage() : string
    {
        return $this->profileImage;
    }

    /**
     * Set the value of profileImage.
     *
     * @return  self
     */
    public function setProfileImage(string $profileImage) : self
    {
        $this->profileImage = $profileImage;
        return $this;
    }

    /**
     * Get the value of salt.
     */
    public function getSalt() : string
    {
        return $this->salt;
    }

    /**
     * Set the value of salt.
     *
     * @return  self
     */
    public function setSalt(string $salt) : self
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Get the value of token.
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * Set the value of token.
     *
     * @return  self
     */
    public function setToken(string $token) : self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get the value of user_cookie.
     */
    public function getUser_cookie() : string
    {
        return $this->user_cookie;
    }

    /**
     * Set the value of user_cookie.
     *
     * @return  self
     */
    public function setUser_cookie(string $user_cookie) : self
    {
        $this->user_cookie = $user_cookie;
        return $this;
    }

    /**
     * Get the value of user_customerID.
     */
    public function getUser_customerID() : string
    {
        return $this->user_customerID;
    }

    /**
     * Set the value of user_customerID.
     *
     * @return  self
     */
    public function setUser_customerID(string $user_customerID) : self
    {
        $this->user_customerID = $user_customerID;

        return $this;
    }

    /**
     * Get the value of remember_cookie.
     */
    public function getRemember_cookie() : string
    {
        return $this->remember_cookie;
    }

    /**
     * Set the value of remember_cookie.
     *
     * @return  self
     */
    public function setRemember_cookie(string $remember_cookie) : self
    {
        $this->remember_cookie = $remember_cookie;
        return $this;
    }

    /**
     * Get the value of token_expire.
     */
    public function getToken_expire() : DateTimeInterface
    {
        return $this->token_expire;
    }

    /**
     * Set the value of token_expire.
     *
     * @return  self
     */
    public function setToken_expire(DateTimeInterface $token_expire) : self
    {
        $this->token_expire = $token_expire;
        return $this;
    }

    /**
     * Get the value of phone.
     */
    public function getPhone() : string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone.
     *
     * @return  self
     */
    public function setPhone(string $phone) : self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get the value of deleted.
     */
    public function getDeleted() : int
    {
        return $this->deleted;
    }

    /**
     * Set the value of deleted.
     *
     * @return  self
     */
    public function setDeleted(int $deleted) : self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get the value of verified.
     */
    public function getVerified() : int
    {
        return $this->verified;
    }

    /**
     * Set the value of verified.
     *
     * @return  self
     */
    public function setVerified(int $verified) : self
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * Get the value of fb_access_token.
     */
    public function getFb_access_token() : string
    {
        return $this->fb_access_token;
    }

    /**
     * Set the value of fb_access_token.
     *
     * @return  self
     */
    public function setFb_access_token(string $fb_access_token) : self
    {
        $this->fb_access_token = $fb_access_token;
        return $this;
    }
}
