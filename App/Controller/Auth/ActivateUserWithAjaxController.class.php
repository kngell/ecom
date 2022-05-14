<?php

declare(strict_types=1);

class ActivateUserWithAjaxController extends Controller
{
    public function index()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validate($data['csrftoken'], $data['frm_name'])) {
                $model = $this->model(ActivateUserManager::class)->assign($data);
            }
        }
    }
}
