<?php

declare(strict_types=1);

interface CoreErrorInterface
{
    public function addError($error, Object $object, array $errorParams = []): self;

    public function getErrors(): array;

    public function getErrorParams(): array;

    public function dispatchError(?string $redirectPath) : self;

    public function or(string $redirect, ?string $message = null): bool;

    public function hasError(): bool;

    public function getErrorCode(): string;
}