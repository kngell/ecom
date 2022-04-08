<?php

declare(strict_types=1);

class SecurityController extends Controller
{
    public function indexPage()
    {
    }

    public function loginPage()
    {
        echo 'login';
    }

    public function registerPage()
    {
        $this->render('users' . DS . 'account' . DS . 'login', [
            'form' => 'Login form', //$this->container->make(RegisterForm::class)->createForm('security' . DS . 'login'),
        ]);
    }
}