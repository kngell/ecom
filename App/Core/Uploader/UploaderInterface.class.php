<?php

declare(strict_types=1);

interface UploaderInterface
{
    public function upload(): string;
}