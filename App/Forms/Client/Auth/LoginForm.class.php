<?php

declare(strict_types=1);
class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    private string $label = '<div>&nbspRemember Me&nbsp</div>';

    public function __construct(?Object $repository = null, ?string $templateName = null)
    {
        parent::__construct($repository, $templateName);
    }

    /**
     * Create Login form.
     *
     * @param string $action
     * @param object|null $dataRepository
     * @param object|null $callingController
     * @return mixed
     */
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'id' => 'login-frm',
            'class' => ['login-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->input([
            EmailType::class => ['name' => 'email'],
        ])->placeholder('Email :')->class('email')->noLabel(), $this->template);
        $this->template = str_replace('{{password}}', (string) $form->input([
            PasswordType::class => ['name' => 'password'],
        ])->placeholder('Password :')->noLabel(), $this->template);
        $this->template = str_replace('{{remamber_me}}', (string) $form->input([
            CheckboxType::class => ['name' => 'remember_me'],
        ])->labelClass('checkbox')->label($this->label)->spanClass('checkbox__box text-danger'), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'signin'],
        ])->label('Login'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}
