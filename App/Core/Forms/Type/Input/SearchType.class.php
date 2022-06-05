<?php

declare(strict_types=1);

class SearchType extends InputType implements FormExtensionTypeInterface
{
    /** @var string - this is the text type extension */
    protected string $type = 'search';
    /** @var array - returns the defaults for the input type */
    protected array $defaults = [];

    /**
     * set Params.
     *
     * @param array $fields
     * @param mixed|null $options
     * @param array $settings
     */
    public function __construct(array $fields, mixed $options = null, array $settings = [])
    {
        /* Assigned arguments to parent InputType constructor */
        parent::__construct($fields, $options, $settings);
    }

    /**
     * @inheritdoc
     *
     * @param array $extensionOptions
     * @return void
     */
    public function configureOptions(array $extensionOptions = []): void
    {
        $this->defaults = [
            /*
             * An <input> element with type="search" that CANNOT contain the
             * following characters: ' or "
             */
            'list' => '',
            'pattern' => YamlFile::get('app')['security']['search_pattern'],
            'maxlength' => '',
            'minlength' => '',
            'placeholder' => '',
            'readonly' => false,
            'size' => '',
            'spellcheck' => '',
            'title' => 'Invalid input',
        ];

        parent::configureOptions($this->defaults);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getExtensionDefaults() : array
    {
        return $this->defaults;
    }

    /**
     * Publicize the default object options to the base class.
     *
     * @return array
     */
    public function getOptions() : array
    {
        return parent::getOptions();
    }

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper.
     *
     * @return array
     */
    public function getSettings() : array
    {
        return parent::getSettings();
    }
}