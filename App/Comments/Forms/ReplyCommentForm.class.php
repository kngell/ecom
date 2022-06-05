<?php

declare(strict_types=1);
class ReplyCommentForm extends ClientFormBuilder implements ClientFormBuilderInterface
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
            'id' => 'reply-comment-frm',
            'class' => ['reply-comment-frm'],
        ]);
        $this->template = str_replace('{{id}}', null !== $dataRepository ? (string) $dataRepository->parent_id : '', $this->template);
        $this->template = str_replace('{{form_begin}}', $form->begin(), $this->template);
        $this->template = str_replace('{{parentID}}', $form->input([
            HiddenType::class => ['name' => 'parentId', 'id' => 'parentId-write'],
        ])->value(null !== $dataRepository ? (string) $dataRepository->parent_id : '')->noLabel()->html(), $this->template);

        $this->template = str_replace('{{content}}', (string) $form->input([
            TextareaType::class => ['name' => 'content', 'id' => 'reply_comment_content'],
        ])->placeholder('Reply here...')->class('content')->noLabel()->html(), $this->template);

        $this->template = str_replace('{{cancel}}', (string) $form->input([
            ButtonType::class => ['type' => 'button', 'id' => 'replyCancelBtn', 'class' => ['btn', 'btn-default', 'btn-outline-secondary', 'submit_btn', 'mt-0', 'ms-2']],
        ])->content('CANCEL'), $this->template);

        $this->template = str_replace('{{submit}}', (string) $form->input([
            ButtonType::class => ['type' => 'submit', 'id' => 'replyCommentBtn', 'name' => 'replyCommentBtn', 'class' => ['btn', 'btn-secondary', 'submit_btn', 'mt-0', 'me-2']],
        ])->content('REPLY'), $this->template);
        $this->template = str_replace('{{form_end}}', $form->end(), $this->template);

        return $this->template;
    }
}