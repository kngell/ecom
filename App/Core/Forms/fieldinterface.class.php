<?php

declare(strict_types=1);
interface FieldInterface
{
    /**
     * Render Field input Type
     * --------------------------------------------------------------------------------------------------.
     * @return string
     */
    public function renderField() : string;

    /**
     * Render Field template
     * --------------------------------------------------------------------------------------------------.
     * @return string
     */

    /**
     * Field Template.
     * --------------------------------------------------------------------------------------------------.
     * @return string
     */
    public function FieldTemplate() : string;

    /**
     * SetModel.
     * --------------------------------------------------------------------------------------------------.
     * @param Model|null $model
     * @return self
     */
    public function setModel(?Model $model = null) : self;

    public function setDefault() : self;

    public function setType() : self;

    public function nestField(string $formID, bool $nestField) : self;

    public function setAttr(string $attribute) : self;

    public function setClass(array $args = []) : self;

    public function setFieldWrapperClass(?string $wrapper) : self;
}
