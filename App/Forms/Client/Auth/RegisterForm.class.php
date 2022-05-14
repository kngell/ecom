<?php

declare(strict_types=1);
class RegisterForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    private string $label = '<div>J\'accepte&nbsp;<a href="#">les termes&nbsp;</a>&amp;&nbsp;<a href="#">conditions</a> d\'utilisation</div>';

    public function __construct(?Object $repository = null, ?string $templateName = null)
    {
        parent::__construct($repository, $templateName);
    }

    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'id' => 'register-frm',
            'class' => ['register-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{camera}}', IMG . 'camera' . DS . 'camera-solid.svg', $this->template);
        $this->template = str_replace('{{avatar}}', IMG . 'users' . DS . 'avatar.png', $this->template);
        $this->template = str_replace('{{last_name}}', (string) $form->input([
            TextType::class => ['name' => 'lastName'],
        ])->placeholder('First Name :')->noLabel(), $this->template);
        $this->template = str_replace('{{first_name}}', (string) $form->input([
            TextType::class => ['name' => 'firstName'],
        ])->placeholder('First Name :')->noLabel(), $this->template);
        $this->template = str_replace('{{username}}', (string) $form->input([
            TextType::class => ['name' => 'userName'],
        ])->placeholder('UserName')->noLabel(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->input([
            EmailType::class => ['name' => 'email', 'id' => 'reg_email'],
        ])->placeholder('Email :')->noLabel(), $this->template);
        $this->template = str_replace('{{password}}', (string) $form->input([
            PasswordType::class => ['name' => 'password', 'id' => 'reg_password'],
        ])->placeholder('Password :')->noLabel(), $this->template);
        $this->template = str_replace('{{cpassword}}', (string) $form->input([
            PasswordType::class => ['name' => 'cpassword'],
        ])->placeholder('Confirm Password :')->noLabel(), $this->template);
        $this->template = str_replace('{{terms}}', (string) $form->input([
            CheckboxType::class => ['name' => 'terms'],
        ])->label($this->label)->labelClass('checkbox')->spanClass('checkbox__box text-danger')->req(), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'reg_singin'], ], null, ['show_label' => false,
            ])->label('Register'), $this->template);
        $this->template = str_replace('{{form_end}}', $this->form()->end(), $this->template);
        return $this->template;
    }
}
