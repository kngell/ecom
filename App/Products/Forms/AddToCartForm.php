<?php

declare(strict_types=1);
class AddToCartForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
            'class' => ['add_to_cart_frm'],
        ]);
        $class = ['btn', 'btn-warning', 'font-size-14', 'form-control'];
        $this->template = str_replace('{{form_begin}}', $form->begin('add_to_cart_frm' . $dataRepository->pdt_id ?? 1), $this->template);

        $this->template = str_replace('{{item}}', (string) $form->input([
            HiddenType::class => ['name' => 'item_id'],
        ])->noLabel()->value($dataRepository->pdt_id), $this->template);
        $this->template = str_replace('{{user}}', (string) $form->input([
            HiddenType::class => ['name' => 'user_id'],
        ])->noLabel()->value('1'), $this->template);
        if (isset($dataRepository->pdt_id) && isset($dataRepository->user_cart) && in_array($dataRepository->pdt_id, $dataRepository->user_cart)) {
            $class = ['btn', 'btn-success', 'font-size-14', 'form-control'];
            $content = 'In the cart';
        }
        $this->template = str_replace('{{button}}', (string) $form->input([
            ButtonType::class => ['type' => 'submit', 'class' => $class],
        ])->content($content ?? 'Add to Cart'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);
        return $this->template;
    }
}