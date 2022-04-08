<?php

declare(strict_types=1);
abstract class CustomValidator
{
    public $success = true;
    public $msg = ',';
    public $field;
    public $rule;

    protected $_model;

    abstract public function runValidation();

    public function setparams($model, $field, $rule, $msg)
    {
        $this->_model = $model ?? '';
        $this->field = $field ?? '';
        $this->rule = $rule ?? '';
        $this->msg = $msg ?? '';

        return $this;
    }

    public function run()
    {
        try {
            $this->success = $this->runValidation();
        } catch (Exception $e) {
            echo 'Validation Exception on ' . get_class() . ' : ' . $e->getMessage() . '<br />';
        }
    }
}
