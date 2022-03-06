<?php

declare(strict_types=1);
class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        return $this->loginFrm();
        // $this->form()
        //     ->add([EmailType::class => ['name' => 'email']])
        //     ->add([PasswordType::class => ['name' => 'password']])
        //     ->add([CheckboxType::class => ['name' => 'remember_me']], null, [
        //         'show_label' => false,
        //         'checkbox_label' => 'Remember Me',
        //         'template' => ['span_class' => 'akono'],
        //     ])
        //     ->add([SubmitType::class => ['name' => 'signin']], null, ['show_label' => false])
        //     ->build(['before' => '<div class="input-box mb-3">', 'after' => '</div>']);
    }

    private function loginFrm() : string
    {
        $form = $this->form([
            'id' => 'login-frm',
            'class' => ['login-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->addField([EmailType::class => ['name' => 'email']])
            ->placeholder('Email :')
            ->class('email'), $this->template);
        $this->template = str_replace('{{password}}', (string) $form->addField([PasswordType::class => ['name' => 'password']])->placeholder('Password :'), $this->template);
        $this->template = str_replace('{{remamber_me}}', (string) $form->addField([CheckboxType::class => ['name' => 'remember_me']])->label('Remember Me'), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->addField([SubmitType::class => ['name' => 'signin']], null, ['show_label' => false]), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}