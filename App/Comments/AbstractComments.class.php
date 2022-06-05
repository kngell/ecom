<?php

declare(strict_types=1);

abstract class AbstractComments implements CommentsInterface
{
    public function timeAgo(string $dateTime, bool $full = false) : string
    {
        $now = new DateTimeImmutable();
        $ago = new DateTimeImmutable($dateTime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = ['y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];
        foreach ($string as $key => &$value) {
            if ($diff->$key) {
                $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            } else {
                unset($string[$key]);
            }
        }
        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' ago' : 'just Now';
    }
}