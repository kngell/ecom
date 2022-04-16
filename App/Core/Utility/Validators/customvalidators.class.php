<?php

declare(strict_types=1);
abstract class CustomValidator
{
    private mixed $rule;
    private ModelInterface $_model;
    private bool $success = true;
    private string $msg;
    private mixed $field;

    public function __construct(ModelInterface $model, mixed $field, mixed $rule, string $msg)
    {
        $this->_model = $model;
        $this->field = $field;
        $this->rule = $rule;
        $this->msg = $msg;
    }

    abstract public function runValidation();

    public function run()
    {
        try {
            return $this->success = $this->runValidation();
        } catch (Exception $e) {
            echo 'Validation Exception on ' . get_class() . ' : ' . $e->getMessage() . '<br />';
        }
    }

    public function getModel() : ModelInterface
    {
        return $this->_model;
    }

    public function getField() : mixed
    {
        return $this->field;
    }

    public function getRule() : mixed
    {
        return $this->rule;
    }

    public function getMsg() : string
    {
        return $this->msg;
    }
}