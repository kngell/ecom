<?php

declare(strict_types=1);
class Commentsxxx extends AbstractComments
{
    protected string $template;
    protected string $layout;
    protected string $html = '';

    public function __construct(protected AddCommentForm $addCommentForm, protected ReplyCommentForm $replyCommentForm, ?string $template = null)
    {
        if (null == $template) {
            $pathLayout = VIEW . 'client' . DS . 'layouts' . DS . 'inc' . DS . 'default' . DS . '_comments_layout.php';
            $pathTemplate = FILES . 'Template' . DS . 'Base' . 'Comments' . DS . 'commentTemplate.php';
            if (file_exists($pathLayout) && file_exists($pathTemplate)) {
                $this->template = file_get_contents($pathTemplate);
                $this->layout = file_get_contents($pathLayout);
            } else {
                throw new CommentsExceptions('Template does not exists!', 0);
            }
        } else {
            $this->template = $template;
        }
    }

    public function showAllComments(array $comments, ?int $totalComments = null, array $filters = [], int $parent_id = -1) : string
    {
        // if ($parent_id != -1) {
        // array_multisort(array_column($comments, 'created_at'), SORT_ASC, $comments);
        // }
        array_multisort(array_column($comments, 'created_at'), SORT_ASC, $comments);
        foreach ($comments as $comment) {
            // if ($comment->parent_id == $parent_id) {
            //     $this->html .= $this->showComment($comment, $comments, $filters);
            // }
            $this->html .= $this->showComment($comment, $totalComments, $comments, $filters);
        }
        return $this->html;
    }

    public function showComment(?object $comment = null, ?int $totalComments = null, array $comments = [], array $filters = []) : string
    {
        if (null != $comment) {
            $content = nl2br(htmlspecialchars($comment->content, ENT_QUOTES));
            $content = str_ireplace(['&lt;i&gt;', '&lt;/i&gt', '&lt;b&gt;', '&lt;u&gt;', '&lt;/u&gt;', '&lt;/u&gt;', '&lt;code&gt;', '&lt;.code&gt;', '&lt;pre&gt;', '&lt;/pre&gt;'], ['<i>', '</i>', '<b>', '</b>', '<u>', '</u>', '<code>', '</code>', '<pre>', '</pre>'], $content);
            if ($filters) {
                $content = str_ireplace(array_column($filters, 'word'), array_column($filters, 'replacement'), $content);
            }
            return $this->fillInCommentTemplate($this->template, $content, $comments, $comment, $totalComments, $filters);
        }
        return '';
    }

    private function fillInCommentTemplate(string $template, string $content, array $comments, ?object $comment, ?int $totalComments, array $filters) : string
    {
        $template = str_replace('{{image}}', !empty($comment->img) ? ImageManager::asset_img(htmlspecialchars($comment->getImg(), ENT_QUOTES)) : ImageManager::asset_img('users' . DS . 'avatar.png') ?? null, $template);
        $template = str_replace('{{TotalComments}}', strval($totalComments), $template);
        $template = str_replace('{{name}}', htmlspecialchars($comment->name ?? '', ENT_QUOTES) ?? null, $template);
        $template = str_replace('{{date}}', $this->timeAgo($comment->created_at) ?? null, $template);
        $template = str_replace('{{comment_content}}', $content . ($comment->approved ? '' : '<br><br><i>(Awaiting Approval)</i>'), $template);
        // $template = str_replace('{{votes}}', (string) $comment->votes ?? null, $template);
        // $template = str_replace('{{id}}', (string) $comment->cmtID ?? '', $template);
        $template = str_replace('{{addCommentForm}}', $this->addCommentForm->createForm('comments' . DS . 'comments', null) ?? null, $template);
        $template = str_replace('{{replyCommentForm}}', $this->replyCommentForm->createForm('comments' . DS . 'comments', $comment) ?? null, $template);
        // $template = str_replace('{{show_comments}}', $this->showAllComments($comments, $filters, $comment->cmtID) ?? null, $template);
        return $template;
    }
}