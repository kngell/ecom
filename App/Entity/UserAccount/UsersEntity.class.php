<?php

declare(strict_types=1);

use DateTimeInterface;

class UsersEntity extends Entity
{
    /** @id */
    private int $userId;
    /** @Last Name */
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
    /** @media */
    private string $profileImage;
    private string $salt;
    private string $userToken;
    private string $userCookie;
    private string $customerId;
    private string $rememberMeCookie;
    /** @var DateTimeInterface */
    private DateTimeInterface $tokenExpire;
    private string $phone;
    private int $deleted;
    private int $verified;
    private string $fbAccessToken;
    private string $terms;
    private string $cpassword;

    public function __construct()
    {
        if (!isset($this->registerDate)) {
            $this->registerDate = new DateTimeImmutable();
        }
    }

    /**
     * Get the value of userID.
     */
    public function getUserId() : int
    {
        return $this->userId;
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
    public function getUserToken() : string
    {
        return $this->userToken;
    }

    /**
     * Set the value of token.
     *
     * @return  self
     */
    public function setUserToken(string $token) : self
    {
        $this->userToken = $token;
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
     * Set the value of userID.
     *
     * @return  self
     */
    public function setUserId($userID)
    {
        $this->userId = $userID;

        return $this;
    }

    public function delete(?string $field = null) : self
    {
        if (isset($this->$field)) {
            unset($this->$field);
        }
        return $this;
    }

    /**
     * Get the value of terms.
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Set the value of terms.
     *
     * @return  self
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Get the value of cpassword.
     */
    public function getCpassword()
    {
        return $this->cpassword;
    }

    /**
     * Set the value of cpassword.
     *
     * @return  self
     */
    public function setCpassword($cpassword)
    {
        $this->cpassword = $cpassword;
        return $this;
    }

    /**
     * Get the value of userCookie.
     */
    public function getUserCookie() : string
    {
        return $this->userCookie;
    }

    /**
     * Set the value of userCookie.
     *
     * @return  self
     */
    public function setUserCookie(string $userCookie) : self
    {
        $this->userCookie = $userCookie;
        return $this;
    }

    /**
     * Get the value of userCustomerID.
     */
    public function getCustomerId() : string
    {
        return $this->customerId;
    }

    /**
     * Set the value of userCustomerID.
     *
     * @return  self
     */
    public function setCustomerId(string $userCustomerID) : self
    {
        $this->customerId = $userCustomerID;
        return $this;
    }

    /**
     * Get the value of rememberCookie.
     */
    public function getRememberMeCookie() : string
    {
        return $this->rememberMeCookie;
    }

    /**
     * Set the value of rememberCookie.
     *
     * @return  self
     */
    public function setRememberMeCookie(string $rememberCookie) : self
    {
        $this->rememberMeCookie = $rememberCookie;
        return $this;
    }

    /**
     * Get the value of tokenExpire.
     */
    public function getTokenExpire() : DateTimeInterface
    {
        return $this->tokenExpire;
    }

    /**
     * Set the value of tokenExpire.
     *
     * @return  self
     */
    public function setTokenExpire(DateTimeInterface $tokenExpire) : self
    {
        $this->tokenExpire = $tokenExpire;
        return $this;
    }

    /**
     * Get the value of fbAccessToken.
     */
    public function getFbAccessToken() : string
    {
        return $this->fbAccessToken;
    }

    /**
     * Set the value of fbAccessToken.
     *
     * @return  self
     */
    public function setFbAccessToken(string $fbAccessToken) : self
    {
        $this->fbAccessToken = $fbAccessToken;
        return $this;
    }

    /**
     * Set the value of registerDate.
     *
     * @return  self
     */
    public function setRegisterDate(DateTimeInterface $registerDate) : self
    {
        $this->registerDate = $registerDate;
        return $this;
    }
}