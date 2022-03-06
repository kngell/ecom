<?php

declare(strict_types=1);
class RegisterForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    private string $label = '<div>J\'accepte&nbsp;<a href="#">les termes&nbsp;</a>&amp;&nbsp;<a href="#">conditions</a> d\'utilisation</div>';

    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        return $this->frm();
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

    private function frm() : string
    {
        $form = $this->form([
            'id' => 'register-frm',
            'class' => ['register-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{camera}}', IMG . 'camera' . DS . 'camera-solid.svg', $this->template);
        $this->template = str_replace('{{avatar}}', IMG . 'users' . DS . 'avatar.png', $this->template);
        $this->template = str_replace('{{last_name}}', (string) $form->addField([TextType::class => ['name' => 'lastName']])->placeholder('First Name :'), $this->template);
        $this->template = str_replace('{{first_name}}', (string) $form->addField([TextType::class => ['name' => 'firstName']])->placeholder('First Name :'), $this->template);
        $this->template = str_replace('{{username}}', (string) $form->addField([TextType::class => ['name' => 'username']])->placeholder('UserName'), $this->template);
        $this->template = str_replace('{{email}}', (string) $form->addField([EmailType::class => ['name' => 'email', 'id' => 'reg_email']])->placeholder('Email :'), $this->template);
        $this->template = str_replace('{{password}}', (string) $form->addField([PasswordType::class => ['name' => 'password', 'id' => 'reg_password']])->placeholder('Password :'), $this->template);
        $this->template = str_replace('{{c_password}}', (string) $form->addField([PasswordType::class => ['name' => 'c_password']])->placeholder('Confirm Password :'), $this->template);
        $this->template = str_replace('{{terms}}', (string) $form->addField([CheckboxType::class => [
            'name' => 'terms',
            'class' => 'checkbox__input',
        ]])
            ->label($this->label)
            ->labelClass('checkbox')
            ->spanClass('checkbox__box text-danger')
            ->req(), $this->template);

        // $this->userFrm->checkbox('terms')->Label('<div>J\'accepte&nbsp;<a href="#">les termes&nbsp;</a>&amp;&nbsp;<a href="#">conditions</a> d\'utilisation</div>')->class('checkbox__input')->spanClass('checkbox__box')->LabelClass('checkbox')

        $this->template = str_replace('{{submit}}', (string) $form->addField([SubmitType::class => ['name' => 'reg_singin']], null, ['show_label' => false]), $this->template);
        $this->template = str_replace('{{form_end}}', $this->form()->end(), $this->template);
        return $this->template;
    }
}