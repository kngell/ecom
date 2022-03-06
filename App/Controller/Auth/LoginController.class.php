<?php

declare(strict_types=1);

class LoginController extends Controller
{
    public function login()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->getAll();
            if ((new Token())->validateToken($data['csrftoken'])) {
                $this->model_instance->assign($data);
                $this->model_instance->validator($data, Form_rules::login());
                if ($this->model_instance->validationPasses()) {
                }
            }
        }
    }

    //=======================================================================
    //Register
    //=======================================================================
    public function register()
    {
        if ($this->request->exists('post')) {
            $data = $this->request->getAll();
            if ((new Token())->validateToken($data['csrftoken'])) {
                $this->model_instance->assign($data);
                $this->model_instance->validator($data, Form_rules::login());
                if ($this->model_instance->validationPasses()) {
                }
            }
        }
    }
}