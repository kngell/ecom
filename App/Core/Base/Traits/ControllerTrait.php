<?php

declare(strict_types=1);

trait ControllerTrait
{
    protected function isIncommingDataValid(Model $m, string $ruleMethod, array $newKeys = []) : void
    {
        method_exists('Form_rules', 'login') ? $m->validator(Form_rules::$ruleMethod()) : '';
        if (!$m->validationPasses()) {
            $this->jsonResponse(['result' => 'error-field', 'msg' => $m->getErrorMessages($newKeys)]);
        }
    }

    protected function uploadFiles(Model $m) : Object
    {
        list($uploaders, $paths) = $this->container->make(UploaderFactory::class, [
            'filesAry' => $this->request->getFiles(),
        ])->create($m);
        if (is_array($uploaders) && !empty($uploaders)) {
            foreach ($uploaders as $uploader) {
                $paths[] = $uploader->upload($m);
            }
        }
        $m->getEntity()->{'set' . ucfirst($m->getEntity()->getField('media'))}(serialize($paths));
        return $m;
    }

    protected function isPostRequest() : array
    {
        if ($this->request->exists('post') && $this->registerFrm->canHandleRequest()) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validate($data['csrftoken'], $data['frm_name'])) {
                return $data;
            }
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Invalid csrf Token!')]);
        }
        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Invalid post Request!')]);
    }
}