<?php

declare(strict_types=1);
interface CommentsInterface
{
    public function showAllComments(array $comments, ?int $totalComments = null) : string;

    public function showComment(?object $comment, ?array $comments = null) : string;

    public function timeAgo(string $dateTime, bool $full = false) : string;
}