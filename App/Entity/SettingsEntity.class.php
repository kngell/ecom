<?php

declare(strict_types=1);

class SettingsEntity extends Entity
{
    private int $setID;
    private string $settingName;
    private string $settingKey;
    private string $settingDescr;
    private string $value;
    private string $status;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of setID.
     */
    public function getSetID() : int
    {
        return $this->setID;
    }

    /**
     * Set the value of setID.
     *
     * @return  self
     */
    public function setSetID(int $setID) : self
    {
        $this->setID = $setID;
        return $this;
    }

    /**
     * Get the value of settingName.
     */
    public function getSettingName() : string
    {
        return $this->settingName;
    }

    /**
     * Set the value of settingName.
     *
     * @return  self
     */
    public function setSettingName(string $settingName) : self
    {
        $this->settingName = $settingName;
        return $this;
    }

    /**
     * Get the value of settingKey.
     */
    public function getSettingKey() : string
    {
        return $this->settingKey;
    }

    /**
     * Set the value of settingKey.
     *
     * @return  self
     */
    public function setSettingKey(string $settingKey) : self
    {
        $this->settingKey = $settingKey;
        return $this;
    }

    /**
     * Get the value of settingDescr.
     */
    public function getSettingDescr() : string
    {
        return $this->settingDescr;
    }

    /**
     * Set the value of settingDescr.
     *
     * @return  self
     */
    public function setSettingDescr(string $settingDescr) : self
    {
        $this->settingDescr = $settingDescr;
        return $this;
    }

    /**
     * Get the value of value.
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * Set the value of value.
     *
     * @return  self
     */
    public function setValue(string $value) : self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     *
     * @return  self
     */
    public function setStatus(string $status) : self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt() : DatetimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     *
     * @return  self
     */
    public function setUpdatedAt(DatetimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : DatetimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @return  self
     */
    public function setCreatedAt(DatetimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}