<?php

declare(strict_types=1);

// use Twig\Environment;
// use Twig\Extension\DebugExtension;
// use Twig\Loader\FilesystemLoader;

class View
{
    public $page_title;
    protected $ressources;
    protected $_head;
    protected $_body;
    protected $_footer;
    protected $_siteTitle = SITE_TITLE;
    protected $_outputBuffer;
    protected $_layout = DEFAULT_LAYOUT;
    protected $view_file;
    protected $view_data;
    protected $file_path;

    /**
     * Main constructor
     * ======================================================================================.
     * @param string $view_file
     * @param array $view_data
     * @param string $file_path
     * @param object $ressources
     */
    public function __construct()
    {
    }

    /**
     * Main destructor
     * ======================================================================================.
     */
    public function __destruct()
    {
        require_once VIEW . strtolower($this->file_path) . 'layouts' . DS . $this->_layout . '.php';
        $this->ressources = null;
        $this->_head = null;
        $this->_body = null;
        $this->_footer = null;
        $this->_siteTitle = null;
        $this->_outputBuffer = null;
        $this->_layout = null;
        $this->view_file = null;
        $this->view_data = null;
        $this->page_title = null;
    }

    public function initParams(string $file_path) : self
    {
        $this->ressources = json_decode(file_get_contents(APP . 'assets.json'));
        $this->file_path = $file_path;

        return $this;
    }

    /**
     * Render View
     * ======================================================================================.
     * @param string $viewname
     * @return void
     */
    public function render(string $viewname = '', array $params = [])
    {
        if (!empty($viewname)) { //$this->view_file != $viewname
            $this->view_file = preg_replace("/\s+/", '', $viewname);
            if (file_exists(VIEW . strtolower($this->file_path) . $this->view_file . '.php')) {
                $this->renderViewContent(VIEW . strtolower($this->file_path) . $this->view_file . '.php', $params);
            } else {
                Rooter::redirect('restricted' . DS . 'index');
            }
        }
    }

    /**
     * Get Content
     * ======================================================================================.
     * @param [type] $type
     * @return void
     */
    public function content($type)
    {
        if ($type == 'head') {
            return $this->_head;
        }
        if ($type == 'body') {
            return $this->_body;
        }
        if ($type == 'footer') {
            return $this->_footer;
        } else {
            return false;
        }
    }

    /**
     * Get Heand Content
     * ======================================================================================.
     * @param string $type
     * @return void
     */
    public function start(string $type)
    {
        $this->_outputBuffer = $type;
        ob_start();
    }

    /**
     * Render Content
     * ======================================================================================.
     * @return void
     */
    public function end()
    {
        if ($this->_outputBuffer == 'head') {
            $this->_head = ob_get_clean();
        } elseif ($this->_outputBuffer == 'body') {
            $this->_body = ob_get_clean();
        } elseif ($this->_outputBuffer == 'footer') {
            $this->_footer = ob_get_clean();
        } else {
            die('you must first run de start method!');
        }
    }

    //=======================================================================
    //Setters
    //=======================================================================

    /**
     * Set Site titile.
     *
     * @param string $title
     * @return void
     */
    public function set_siteTitle(string $title = '')
    {
        $this->_siteTitle = $title;
    }

    /**
     * Set Layout.
     *
     * @param string $path
     * @return void
     */
    public function set_Layout(string $path) : self
    {
        $this->_layout = $path;

        return $this;
    }

    /**
     * Set Page Title.
     *
     * @param string $p_title
     * @return void
     */
    public function set_pageTitle($p_title = '')
    {
        $this->page_title = $p_title;
    }

    /**
     * Set View Data.
     *
     * @param mixed $data
     * @return void
     */
    public function set_viewData($data)
    {
        $this->view_data = $data;
    }

    public function set_path(string $path) : self
    {
        $this->file_path = $path;
        return $this;
    }
    //=======================================================================
    //Gettters
    //=======================================================================

    /**
     * Get Site title.
     *
     * @return void
     */
    public function get_siteTitle()
    {
        return $this->_siteTitle;
    }

    /**
     * Get Methog.
     *
     * @return void
     */
    public function get_Method()
    {
        return (explode('\\', $this->view_file))[1];
    }

    /**
     * Get Page Title.
     *
     * @param string $p_title
     * @return void
     */
    public function get_pageTitle()
    {
        return $this->page_title;
    }

    /**
     * Get Assets Js Css.
     *
     * @param string $asset
     * @param string $ext
     * @return void
     */
    public function asset($asset = '', $ext = '')
    {
        $root = isset($asset) ? explode('/', $asset) : [];
        if ($root) {
            $path = '';
            $check = array_shift($root);
            $i = 0;
            foreach ($root as $value) {
                $separator = ($i == count($root) - 1) ? '' : US;
                $path .= $value . $separator;
                $i++;
            }
            switch ($check) {
                case 'img':
                    return ASSET_SERVICE_PROVIDER ? ASSET_SERVICE_PROVIDER . US . IMG . $path : IMG . $asset;
                break;
                case 'fonts':
                    return ASSET_SERVICE_PROVIDER ? ASSET_SERVICE_PROVIDER . US . FONT . $path : FONT . $asset;
                break;
                default:
                    if (isset($this->ressources->$asset)) {
                        return ASSET_SERVICE_PROVIDER ? ASSET_SERVICE_PROVIDER . $this->ressources->$asset->$ext ?? '' : $this->ressources->$asset->$ext ?? '';
                    }
            }
        }

        return '';
    }

    protected function renderViewContent($view, array $params = []) :void
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        require_once $view;
    }
}