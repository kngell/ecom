<?php

declare(strict_types=1);

class CommentsEntity extends Entity
{
    /** @id */
    private int $cmtId;
    private string $title;
    private int $pageId;
    private int $userId;
    private string $name;
    private int $parentId;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;
    private string $content;
    private int $votes;
    /** @media */
    private string $img;
    private int $approved = COMMENT_APPROVAL_REQUIRED;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of cmtID.
     */
    public function getCmtId() : int
    {
        return $this->cmtId;
    }

    /**
     * Set the value of cmtID.
     *
     * @return  self
     */
    public function setCmtId(int $cmtID) : self
    {
        $this->cmtId = $cmtID;
        return $this;
    }

    /**
     * Get the value of pageId.
     */
    public function getPageId() : int
    {
        return $this->pageId;
    }

    /**
     * Set the value of pageId.
     *
     * @return  self
     */
    public function setPageId(int $pageId) : self
    {
        $this->pageId = $pageId;
        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @return  self
     */
    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of parentId.
     */
    public function getParentId() : int
    {
        return $this->parentId;
    }

    /**
     * Set the value of parentId.
     *
     * @return  self
     */
    public function setParentId(int $parentId) : self
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     *
     * @return  self
     */
    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the value of content.
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Set the value of content.
     *
     * @return  self
     */
    public function setContent(string $content) : self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the value of votes.
     */
    public function getVotes() : int
    {
        return $this->votes;
    }

    /**
     * Set the value of votes.
     *
     * @return  self
     */
    public function setVotes(int $votes) : self
    {
        $this->votes = $votes;
        return $this;
    }

    /**
     * Get the value of img.
     */
    public function getImg() : string
    {
        return $this->img;
    }

    /**
     * Set the value of img.
     *
     * @return  self
     */
    public function setImg(string $img) : self
    {
        $this->img = $img;
        return $this;
    }

    /**
     * Get the value of approved.
     */
    public function getApproved() : int
    {
        return $this->approved;
    }

    /**
     * Set the value of approved.
     *
     * @return  self
     */
    public function setApproved(int $approved) : self
    {
        $this->approved = $approved;
        return $this;
    }

    /**
     * Get the value of title.
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @return  self
     */
    public function setTitle($title) : self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt() : DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     *
     * @return  self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the value of userID.
     */
    public function getUserId() : int
    {
        return $this->userId;
    }

    /**
     * Set the value of userID.
     *
     * @return  self
     */
    public function setUserId(int $userID) : self
    {
        $this->userId = $userID;
        return $this;
    }
}