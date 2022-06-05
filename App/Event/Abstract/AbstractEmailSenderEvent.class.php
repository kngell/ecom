<?php

declare(strict_types=1);

abstract class AbstractEmailSenderEvent extends Event
{
    protected string $userName;
    protected array $host = [];
    protected string $preheadText;
    protected string $msgTitle;
    protected string $msgBody;
    protected string $btnText;
    protected string $msgEnd;
    protected string $link;
    protected string $email;

    /**
     * Get the value of userName.
     */
    public function getUserName()
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
     * Get the value of host.
     */
    public function getHost() : array
    {
        return $this->host;
    }

    /**
     * Set the value of host.
     *
     * @return  self
     */
    public function setHost(array $host) : self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get the value of preheadText.
     */
    public function getPreheadText() : string
    {
        return $this->preheadText;
    }

    /**
     * Set the value of preheadText.
     *
     * @return  self
     */
    public function setPreheadText(string $preheadText) : self
    {
        $this->preheadText = $preheadText;
        return $this;
    }

    /**
     * Get the value of msgTitle.
     */
    public function getMsgTitle() : string
    {
        return $this->msgTitle;
    }

    /**
     * Set the value of msgTitle.
     *
     * @return  self
     */
    public function setMsgTitle(string $msgTitle) : self
    {
        $this->msgTitle = $msgTitle;
        return $this;
    }

    /**
     * Get the value of msgBody.
     */
    public function getMsgBody() : string
    {
        return $this->msgBody;
    }

    /**
     * Set the value of msgBody.
     *
     * @return  self
     */
    public function setMsgBody(string $msgBody) : self
    {
        $this->msgBody = $msgBody;
        return $this;
    }

    /**
     * Get the value of btnText.
     */
    public function getBtnText() : string
    {
        return $this->btnText;
    }

    /**
     * Set the value of btnText.
     *
     * @return  self
     */
    public function setBtnText(string $btnText) : self
    {
        $this->btnText = $btnText;
        return $this;
    }

    /**
     * Get the value of msgEnd.
     */
    public function getMsgEnd() : string
    {
        return $this->msgEnd;
    }

    /**
     * Set the value of msgEnd.
     *
     * @return  self
     */
    public function setMsgEnd(string $msgEnd) : self
    {
        $this->msgEnd = $msgEnd;
        return $this;
    }

    /**
     * Get the value of link.
     */
    public function getLink() : string
    {
        return $this->link;
    }

    /**
     * Set the value of link.
     *
     * @return  self
     */
    public function setLink(string $link) : self
    {
        $this->link = $link;
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
}