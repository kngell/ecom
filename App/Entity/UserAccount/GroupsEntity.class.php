<?php

declare(strict_types=1);

class GroupsEntity extends Entity
{
    /** @id */
    private int $grId;
    /** @Status */
    private string $status;
    private string $name;
    private string $description;
    /** @var DateTimeInterface */
    private DateTimeInterface $dateEnreg;
    /** @var DateTimeInterface */
    private DateTimeInterface $updateAt;
    private int $parentID;
    private int $deleted;

    public function __construct()
    {
        $this->dateEnreg = !isset($this->dateEnreg) ? new DateTimeImmutable() : $this->dateEnreg;
    }

    /**
     * Get the value of grID.
     */
    public function getGrId()
    {
        return $this->grId;
    }

    /**
     * Set the value of grID.
     *
     * @return  self
     */
    public function setGrId($grID)
    {
        $this->grId = $grID;

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
    public function getParentId()
    {
        return $this->parentID;
    }

    /**
     * Set the value of parentID.
     *
     * @return  self
     */
    public function setParentId($parentID)
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

    public function delete(?string $field = null) : self
    {
        unset($this->$field);
        return $this;
    }

    /**
     * Get the value of dateEnreg.
     */
    public function getDateEnreg() : DateTimeInterface
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
}