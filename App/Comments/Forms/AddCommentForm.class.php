<?php

declare(strict_types=1);
class AddCommentForm extends ClientFormBuilder implements ClientFormBuilderInterface
{
    public function __construct(private FormBuilderBlueprint $frmPrint, ?Object $repository = null, ?string $templateName = null)
    {
        $path = APP . 'Comments' . DS . 'Templates' . DS . ($templateName ?? $this::class) . 'Template.php';
        if (file_exists($path)) {
            $this->template = file_get_contents($path);
        }
        parent::__construct($repository);
    }

    public function createForm(string $action, ?object $dataRepository = null, ?object $callingController = null) : mixed
    {
        $form = $this->form([
            'action' => $action,
            'id' => 'add-comment-frm',
            'class' => ['add-comment-frm'],
        ]);
        $this->template = str_replace('{{id}}', null !== $dataRepository ? (string) $dataRepository->parent_id : '', $this->template);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);

        $this->template = str_replace('{{content}}', (string) $form->input([
            TextareaType::class => ['name' => 'content', 'id' => 'new_comment_content'],
        ])->placeholder('Write your comment here...')->class('content')->noLabel()->req()->rows(2)->cols(30), $this->template);

        $this->template = str_replace('{{submit}}', (string) $form->input([
            ButtonType::class => ['type' => 'submit', 'id' => 'addCommentBtn', 'class' => ['btn', 'btn-primary', 'submit_btn', 'mt-0', 'float-end']],
        ])->content('COMMENT'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);

        return $this->template;
    }
}