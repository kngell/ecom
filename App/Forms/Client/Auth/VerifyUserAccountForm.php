<?php

declare(strict_types=1);
class VerifyUserAccountForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function __construct(?Object $repository = null, ?string $templateName = null)
    {
        parent::__construct($repository, $templateName);
    }

    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'id' => 'verify-frm',
            'class' => ['forgot-frm'],
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->input([
            EmailType::class => ['name' => 'email', 'id' => 'verify_email'],
        ])->placeholder('Email :')->class('email')->noLabel(), $this->template);
        $this->template = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'verify-btn'],
        ])->label('Send Link'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}