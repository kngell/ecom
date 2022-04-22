<?php

declare(strict_types=1);

class GroupsEntity extends Entity
{
    /** @id */
    private int $grID;
    /** @Status */
    private string $status;
    private string $name;
    private string $description;
    /** @var DateTimeInterface */
    private DateTimeInterface $date_enreg;
    /** @var DateTimeInterface */
    private DateTimeInterface $updateAt;
    private int $parentID;
    private int $deleted;

    public function __construct()
    {
        $this->date_enreg = !isset($this->date_enreg) ? new DateTimeImmutable() : $this->date_enreg;
    }

    /**
     * Get the value of grID.
     */
    public function getGrID()
    {
        return $this->grID;
    }

    /**
     * Set the value of grID.
     *
     * @return  self
     */
    public function setGrID($grID)
    {
        $this->grID = $grID;

        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Get the value of updateAt.
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set the value of updateAt.
     *
     * @return  self
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get the value of parentID.
     */
    public function getParentID()
    {
        return $this->parentID;
    }

    /**
     * Set the value of parentID.
     *
     * @return  self
     */
    public function setParentID($parentID)
    {
        $this->parentID = $parentID;

        return $this;
    }

    /**
     * Get the value of deleted.
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set the value of deleted.
     *
     * @return  self
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function delete(string $field) : void
    {
        unset($this->$field);
    }
}