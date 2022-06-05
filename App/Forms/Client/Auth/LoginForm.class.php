<?php

declare(strict_types=1);
class LoginForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    private string $label = '<div>&nbspRemember Me&nbsp</div>';

    public function __construct(private FormBuilderBlueprint $print, ?Object $repository = null, ?string $templateName = null)
    {
        $path = FILES . 'Template' . DS . 'Users' . DS . 'Auth' . DS . 'Forms' . DS . ($templateName ?? $this::class) . 'Template.php';
        if (file_exists($path)) {
            $this->template = file_get_contents($path);
        }
        parent::__construct($repository);
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
        $this->template = str_replace('{{email}}', $form->input($this->print->email(name:'email'))
            ->placeholder('Email :')
            ->class('email')
            ->id('email')
            ->noLabel()
            ->html(), $this->template);
        $this->template = str_replace('{{password}}', $form->input($this->print->password(name:'password'))
            ->placeholder('Password :')
            ->id('passwords')
            ->noLabel()
            ->html(), $this->template);
        $this->template = str_replace('{{remamber_me}}', $form->input($this->print->checkbox(name:'remember_me'))
            ->labelClass('checkbox')
            ->label($this->label)
            ->spanClass('checkbox__box text-danger')
            ->id('remember_me')
            ->html(), $this->template);
        $this->template = str_replace('{{submit}}', $form->input($this->print->submit(name: 'sigin'))
            ->label('Login')->id('sigin')
            ->html(), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}