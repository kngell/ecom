<?php

declare(strict_types=1);
class CommentsFactory
{
    public function __construct(private CommentsInterface $comment)
    {
    }

    public function create()
    {
        if ($this->comment instanceof CommentsInterface) {
            return $this->comment;
        }
    }
}