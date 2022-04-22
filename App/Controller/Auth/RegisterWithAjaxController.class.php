<?php

declare(strict_types=1);

class RegisterWithAjaxController extends Controller
{
    public function index(array $args = []) : void
    {
        if ($this->request->exists('post') && $this->registerFrm->canHandleRequest()) {
            $data = $this->request->get();
            if ($data['csrftoken'] && $this->token->validateToken($data['csrftoken'], $data['frm_name'])) {
                $model = $this->model(RegisterManager::class)->assign($data);
                $this->isIncommingDataValid(m: $model, ruleMethod:'users', newKeys: [
                    'email' => 'reg_email',
                    'password' => 'reg_password',
                    'cpassword' => 'c_password',
                ]);
            }
        }
    }
}