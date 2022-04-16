<?php

declare(strict_types=1);

abstract class AbstractView implements ViewInterface
{
    protected string  $page_title;
    protected string $_siteTitle = SITE_TITLE;
    protected string $_layout = DEFAULT_LAYOUT;
    protected string $file_path;
    protected mixed $view_data;

    /**
     * Set Site titile.
     * ---------------------------------------------.
     * @param string $title
     * @return self
     */
    public function siteTitle(string $title = '') : self
    {
        $this->_siteTitle = $title;
        return $this;
    }

    /**
     * Set Layout.
     * ---------------------------------------------.
     * @param string $path
     * @return void
     */
    public function layout(string $path) : self
    {
        $this->_layout = $path;
        return $this;
    }

    /**
     * Set Page Title.
     * ---------------------------------------------.
     * @param string $p_title
     * @return self
     */
    public function pageTitle(string $p_title = '') : self
    {
        $this->page_title = $p_title;
        return $this;
    }

    /**
     * Set View Data.
     * ------------------------------------------.
     * @param array ...$data
     * @return self
     */
    public function viewData(array ...$data) : self
    {
        $this->view_data = $data;
        return $this;
    }

    /**
     * Set Path.
     * ------------------------------------------.
     * @param string $path
     * @return self
     */
    public function path(string $path) : self
    {
        $this->file_path = $path;
        return $this;
    }

    /**
     * Get Site title.
     * ------------------------------------------.
     * @return string
     */
    public function getSiteTitle() : string
    {
        return $this->_siteTitle;
    }

    /**
     * Get Controller Methog.
     * ----------------------------------------------.
     * @return string
     */
    public function getMethod() : string
    {
        return (explode('\\', $this->view_file))[1];
    }

    /**
     * Get Page Title.
     * -------------------------------------------.
     * @param string $p_title
     * @return string
     */
    public function getPageTitle() : string
    {
        return $this->page_title;
    }

    /**
     * Get Layout.
     * -------------------------------------------.
     * @return string
     */
    public function getLayout() :string
    {
        return $this->_layout;
    }

    /**
     * Get Path.
     * -------------------------------------------.
     * @return string
     */
    public function getPath() :string
    {
        return $this->file_path;
    }

    public function reset() : self
    {
        // $this->_head = '';
        // $this->_body = '';
        // $this->_footer = '';
        $this->_html = '';
        return $this;
    }
}