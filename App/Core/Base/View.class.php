<?php

declare(strict_types=1);
class View extends AbstractView implements ViewInterface
{
    private StdClass $ressources;
    private string $_head;
    private string $_body;
    private string $_footer;
    private string $_html;
    private string $_outputBuffer;
    private string $view_file;
    private string $loginFrm;
    private string $registerFrm;
    private string $search_box;
    private Token $token;
    private ResponseHandler $response;

    /**
     * Main constructor
     * ======================================================================================.
     * @param string $view_file
     * @param array $view_data
     * @param string $file_path
     * @param object $ressources
     */
    public function __construct(array $viewAry = [])
    {
        $this->ressources = json_decode(file_get_contents(APP . 'assets.json'));
        if (!empty($viewAry)) {
            foreach ($viewAry as $key => $value) {
                if ($key != '' && property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /** @inheritDoc */
    public function render(string $viewname = '', array $params = []) : void
    {
        if (!empty($viewname)) { //$this->view_file != $viewname
            $this->view_file = preg_replace("/\s+/", '', $viewname);
            if (file_exists(VIEW . strtolower($this->file_path) . $this->view_file . '.php')) {
                $this->renderViewContent(VIEW . strtolower($this->file_path) . $this->view_file . '.php', $params);
            } else {
                //Rooter::redirect('restricted' . DS . 'index');
            }
        }
    }

    /** @inheritDoc */
    public function start(string $type) : void
    {
        $this->_outputBuffer = $type;
        ob_start();
    }

    /** @inheritDoc */
    public function content(string $type) : bool|string
    {
        return match ($type) {
            'head' => $this->_head,
            'body' => $this->_body,
            'footer' => $this->_footer,
            'html' => $this->_html,
            default => false
        };
    }

    /** @inheritDoc */
    public function end() : void
    {
        isset($this->_outputBuffer) ? $this->{'_' . $this->_outputBuffer} = ob_get_clean() : '';
    }

    /** @inheritDoc */
    public function asset($asset = '', $ext = '') : string
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

    private function renderViewContent($view, array $params = []) : void
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        require_once $view;
        $this->start('html');
        require_once VIEW . strtolower($this->file_path) . 'layouts' . DS . $this->_layout . '.php';
        $this->end();
        $this->response->handler()->setContent($this->content('html'))->send();
    }
}