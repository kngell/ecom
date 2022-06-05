<?php

declare(strict_types=1);
class ProceedToBuyForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function __construct(private FormBuilderBlueprint $frmPrint, ?Object $repository = null, ?string $templateName = null)
    {
        $path = APP . 'Products' . DS . 'Phones' . DS . 'Templates' . DS . lcfirst(($templateName ?? $this::class) . 'Template.php');
        if (file_exists($path)) {
            $this->template = file_get_contents($path);
        }
        parent::__construct($repository);
    }

    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'class' => ['Proceed_to_buy_frm'],
        ]);
        $class = ['btn', 'btn-danger', 'font-size-14', 'form-control'];
        $this->template = str_replace('{{form_begin}}', $form->begin('Proceed_to_buy_frm'), $this->template);
        $this->template = str_replace('{{button}}', (string) $form->input([
            ButtonType::class => ['type' => 'submit', 'class' => $class],
        ])->content('Proceed to buy'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}