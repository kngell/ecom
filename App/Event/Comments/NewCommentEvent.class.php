<?php

declare(strict_types=1);

class NewCommentEvent extends Event implements EventsInterface
{
    private int $maxCommentToShow = 30;

    public function getName(): string
    {
        return $this::class;
    }

    /**
     * Get the value of maxCommentToShow.
     */
    public function getMaxCommentToShow() : int
    {
        return $this->maxCommentToShow;
    }

    /**
     * Set the value of maxCommentToShow.
     *
     * @return  self
     */
    public function setMaxCommentToShow($maxCommentToShow) :self
    {
        $this->maxCommentToShow = $maxCommentToShow;
        return $this;
    }
}