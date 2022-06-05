<?php

declare(strict_types=1);

class GroupUserEntity extends Entity
{
    /** @id */
    private int $gruId;
    /** @userID */
    private int $userID;
    private int $groupID;
    /** @var DateTimeInterface */
    private DateTimeInterface $dateEnreg;
    /** @var DateTimeInterface */
    private DateTimeInterface $updateAt;

    public function __construct()
    {
        $this->dateEnreg = !isset($this->dateEnreg) ? new DateTimeImmutable() : $this->dateEnreg;
    }

    /**
     * Get the value of gruID.
     */
    public function getGruId()
    {
        return $this->gruId;
    }

    /**
     * Get the value of userID.
     */
    public function getUserId()
    {
        return $this->userId;
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

    /**
     * Get the value of groupID.
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set the value of groupID.
     *
     * @return  self
     */
    public function setGroupId($groupID)
    {
        $this->groupId = $groupID;

        return $this;
    }

    public function delete(?string $field = null) : self
    {
        unset($this->$field);
        return $this;
    }

    /**
     * Get the value of dateEnreg.
     */
    public function getDateEnreg(): DateTimeInterface
    {
        return $this->dateEnreg;
    }

    /**
     * Set the value of dateEnreg.
     *
     * @return  self
     */
    public function setDateEnreg(DateTimeInterface $dateEnreg) : self
    {
        $this->dateEnreg = $dateEnreg;
        return $this;
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
}