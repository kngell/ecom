<?php

declare(strict_types=1);
class SelectOptions
{
    private array $optionGlobalAttr = [];
    private array $options;

    public function __construct(array $optionGlobalAttr = [], array $options = [])
    {
        $this->optionGlobalAttr = $optionGlobalAttr;
        $this->options = $options;
    }

    public function getSettings() : array
    {
        return $this->settings;
    }

    /**
     * Get the value of optionGlobalAttr.
     */
    public function getOptionGlobalAttr() : array
    {
        return $this->optionGlobalAttr;
    }

    /**
     * Set the value of optionGlobalAttr.
     *
     * @return  self
     */
    public function setOptionGlobalAttr(array $optionGlobalAttr) : self
    {
        $this->optionGlobalAttr = $optionGlobalAttr;
        return $this;
    }

    /**
     * Get the value of options.
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Set the value of options.
     *
     * @return  self
     */
    public function setOptions(array $options) : self
    {
        $this->options = $options;
        return $this;
    }
}