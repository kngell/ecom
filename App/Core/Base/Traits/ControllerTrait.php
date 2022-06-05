<?php

declare(strict_types=1);

trait ControllerTrait
{
    /**
     * Get the value of commentOutput.
     */
    public function outputComments() : array
    {
        return array_filter($this->customProperties, function ($prop) {
            return in_array($prop, ['comments']);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Set the value of commentOutput.
     *
     * @return  self
     */
    public function setCommentsArg(mixed $commentOutput) : self
    {
        $this->customProperties['comments'] = $commentOutput;
        return $this;
    }

    public function getSettings() : Object
    {
        if (!$this->cache->exists('settings')) {
            $this->cache->set('settings', $this->container(SettingsManager::class)->getSettings());
        }
        return $this->cache->get('settings');
    }

    protected function isIncommingDataValid(Model $m, string $ruleMethod, array $newKeys = []) : void
    {
        method_exists('Form_rules', $ruleMethod) ? $m->validator(Form_rules::$ruleMethod()) : '';
        if (!$m->validationPasses()) {
            $this->jsonResponse(['result' => 'error-field', 'msg' => $m->getErrorMessages($newKeys)]);
        }
    }

    protected function uploadFiles(Model $m) : Object
    {
        list($uploaders, $paths) = $this->container(UploaderFactory::class, [
            'filesAry' => $this->request->getFiles(),
        ])->create($m);
        if (is_array($uploaders) && !empty($uploaders)) {
            foreach ($uploaders as $uploader) {
                $paths[] = $uploader->upload($m);
            }
        }
        $m->getEntity()->{'set' . ucfirst($m->getEntity()->getFieldWithDoc('media'))}(serialize($paths));
        return $m;
    }

    protected function isValidRequest(?string $csrfName = null) : array
    {
        $data = $this->request->get();
        if ($data['csrftoken'] && $this->token->validate($data['csrftoken'], $csrfName == null ? $data['frm_name'] : $csrfName)) {
            return $data;
        }
        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Invalid csrf Token!')]);

        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Invalid post Request!')]);
    }

    /**
     * Init controller.
     * ==================================================================.
     * @param array $params
     * @return self
     */
    protected function properties(array $params = []) : self
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($key != '' && property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        return $this;
    }

    protected function container(?string $class = null, array $args = []) : object
    {
        if (null != $class) {
            return Application::diGet($class, $args);
        }
        return Application::getInstance();
    }

    private function AuthenticationFroms() : array
    {
        return [
            'loginFrm' => $this->container(LoginForm::class)->createForm('login' . DS . 'login'),
            'registerFrm' => $this->container(RegisterForm::class)->createForm('register' . DS . 'register'),
            'forgotFrm' => $this->container(ForgotPasswordForm::class)->createForm('forgot' . DS . 'forgot'),
        ];
    }

    private function searchBox() : array
    {
        return ['search_box' => file_get_contents(FILES . 'Template' . DS . 'Base' . DS . 'search_box.php')];
    }
}