<?php

declare(strict_types=1);

class CoreError implements CoreErrorInterface
{
    /** @var string */
    public const    SHORT_PASSWORD = 'ERR_100MC';
    public const    PASSWORD_LETTER = 'ERR_150MC';
    public const    PASSWORD_NUMBER = 'ERR_200MC';
    public const    INVALID_EMAIL = 'ERR_250MC';
    public const    EMPTY_FIELDS = 'ERR_300MC';

    /** @var array */
    protected array $errors = [];
    /** @var array */
    protected array $errorParams = [];
    /** @var string */
    protected ?string $errorCode = null;
    /** @var object */
    protected Object $object;
    /** @var bool */
    protected bool $hasError = false;

    /**
     * Add a error to the error array.
     *
     * @param array|string $error
     * @param object $object
     * @param array $errorParams
     * @return CoreError
     */
    public function addError($error, Object $object, array $errorParams = []): self
    {
        if ($error) {
            $this->errors = $error;
        }
        if ($object) {
            $this->object = $object;
        }
        return $this;
    }

    /**
     * Dispatched one or more errors if necessary.
     *
     * @return CoreErrorInterface
     */
    public function dispatchError(?string $redirectPath = null): CoreErrorInterface
    {
        if (is_array($this->errors) && count($this->errors) > 0) {
            $this->hasError = true; /* If array contains at least 1 element then we have an error */
            foreach ($this->errors as $error) {
                $keys = array_keys((array) $error);
                foreach ($error as $err) {
                    $this->errorCode = $keys[0];
                    $this->object->flashMessage($err, $this->object->flashWarning());
                    $this->object->redirect(($redirectPath !== null) ? $redirectPath : $this->object->onSelf());
                }
            }
        }
        $this->hasError = false;
        return $this;
    }

    /**
     * Return bool if the user was successfully updated.
     *
     * @param string $redirect
     * @param string|null $message
     * @return bool
     */
    public function or(string $redirect, ?string $message = null): bool
    {
        if (!$this->hasError) {
            $message = (null == !$message) ? $message : 'Changes Saved!';
            $this->object->flashMessage($message, $this->object->flashSuccess());
            $this->object->redirect($redirect);

            return true;
        }
        return false;
    }

    /**
     * Return Whether we have error or not.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->hasError;
    }

    /**
     * Returns the array of errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Returns an array of error parameters.
     *
     * @return array
     */
    public function getErrorParams(): array
    {
        return $this->errorParams;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Returns the error whcih matches the error code and returned a formatted array
     * to be dispatched.
     *
     * @param string $code
     * @return array
     */
    public static function display(string $code): array
    {
        $error = YamlFile::get('error')[$code];
        if ($error) {
            return [$code => $error];
        }
    }
}