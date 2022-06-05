<?php

declare(strict_types=1);

class EmailSenderConfiguration
{
    private string $subject = 'Welcome!';
    private string $cssPath = ASSET . 'css' . DS . 'custom' . DS . 'client' . DS . 'users' . DS . 'email' . DS . 'main.css';
    private string $emailTemplate = 'users' . DS . 'emailTemplate' . DS . 'welcomeTemplate';
    private string $rootPath = 'Client/';
    private string $layout = 'emailTemplate';
    private array $from = ['email' => 'contact@kngell.com', 'name' => ''];

    public function __construct()
    {
    }

    /**
     * Get the value of subject.
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * Set the value of subject.
     *
     * @return  self
     */
    public function setSubject($subject) : self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get the value of cssPath.
     */
    public function getCssPath() : string
    {
        if (file_exists($this->cssPath)) {
            $this->cssPath = file_get_contents($this->cssPath);
            return $this->cssPath;
        }
        return '';
    }

    /**
     * Set the value of cssPath.
     *
     * @return  self
     */
    public function setCssPath($cssPath) : self
    {
        $this->cssPath = $cssPath;
        return $this;
    }

    /**
     * Get the value of emailTemplate.
     */
    public function getEmailTemplate() : string
    {
        return $this->emailTemplate;
    }

    /**
     * Set the value of emailTemplate.
     *
     * @return  self
     */
    public function setEmailTemplate($emailTemplate) : self
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * Get the value of rootPath.
     */
    public function getRootPath() : string
    {
        return $this->rootPath;
    }

    /**
     * Set the value of rootPath.
     *
     * @return  self
     */
    public function setRootPath($rootPath) : self
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    /**
     * Get the value of layout.
     */
    public function getLayout() : string
    {
        return $this->layout;
    }

    /**
     * Set the value of layout.
     *
     * @return  self
     */
    public function setLayout($layout) : self
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the value of from.
     */
    public function getFrom() : array
    {
        return $this->from;
    }

    /**
     * Set the value of from.
     *
     * @return  self
     */
    public function setFrom(null|string $from = '', null|string $name = '') : self
    {
        $from !== '' ? $this->from['email'] = $from : '';
        $name !== '' ? $this->from['name'] = $name : '';
        return $this;
    }
}