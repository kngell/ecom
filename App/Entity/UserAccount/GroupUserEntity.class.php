<?php

declare(strict_types=1);

class GroupUserEntity extends Entity
{
    /** @id */
    private int $gruID;
    /** @userID */
    private int $userID;
    private int $groupID;
    /** @var DateTimeInterface */
    private DateTimeInterface $date_enreg;
    /** @var DateTimeInterface */
    private DateTimeInterface $update_At;

    public function __construct()
    {
        $this->date_enreg = new DateTimeImmutable();
    }

    /**
     * Get the value of gruID.
     */
    public function getGruID()
    {
        return $this->gruID;
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
     * Get the value of groupID.
     */
    public function getGroupID()
    {
        return $this->groupID;
    }

    /**
     * Set the value of groupID.
     *
     * @return  self
     */
    public function setGroupID($groupID)
    {
        $this->groupID = $groupID;

        return $this;
    }

    /**
     * Get the value of date_enreg.
     */
    public function getDate_enreg()
    {
        return $this->date_enreg;
    }

    /**
     * Get the value of update_At.
     */
    public function getUpdate_At()
    {
        return $this->update_At;
    }

    /**
     * Set the value of update_At.
     *
     * @return  self
     */
    public function setUpdate_At($update_At)
    {
        $this->update_At = $update_At;

        return $this;
    }

    public function delete(string $field) : void
    {
        unset($this->$field);
    }
}