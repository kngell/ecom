<?php

declare(strict_types=1);
class ForgotPasswordForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'id' => 'forgot-frm',
            'class' => ['forgot-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->input([
            EmailType::class => ['name' => 'email', 'id' => 'forgot_email'],
        ])->placeholder('Email :')->class('email')->noLabel(), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'forgot'],
        ])->label('Reset Password'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}