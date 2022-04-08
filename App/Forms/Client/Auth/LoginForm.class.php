<?php

declare(strict_types=1);
class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'id' => 'login-frm',
            'class' => ['login-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->input([EmailType::class => ['name' => 'email']])
            ->placeholder('Email :')
            ->class('email'), $this->template);
        $this->template = str_replace('{{password}}', (string) $form->input([PasswordType::class => ['name' => 'password']])->placeholder('Password :'), $this->template);
        $this->template = str_replace('{{remamber_me}}', (string) $form->input([CheckboxType::class => ['name' => 'remember_me']])->label('Remember Me'), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->input([SubmitType::class => ['name' => 'signin']])->label('Login'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}