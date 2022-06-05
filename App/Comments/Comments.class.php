<?php

declare(strict_types=1);
class Comments extends AbstractComments
{
    protected string $template;
    protected string $layout;
    protected string $html = '';
    protected array $children = [];
    protected array $comments = [];
    protected stdClass $comment;
    protected int $cmtID;

    public function __construct(protected AddCommentForm $addCommentForm, protected ReplyCommentForm $replyCommentForm)
    {
        $pathLayout = VIEW . 'client' . DS . 'layouts' . DS . 'inc' . DS . 'default' . DS . '_comments_layout.php';
        $pathTemplate = APP . 'Comments' . DS . 'Templates' . DS . 'commentTemplate.php';
        if (file_exists($pathLayout) && file_exists($pathTemplate)) {
            $this->template = file_get_contents($pathTemplate);
            $this->layout = file_get_contents($pathLayout);
        } else {
            throw new CommentsExceptions('template does not exists!', 0);
        }
    }

    public function showAllComments(array $comments, ?int $totalComments = null) : string
    {
        array_multisort(array_column($comments, 'created_at'), SORT_DESC, $comments);
        foreach ($comments as $comment) {
            $this->html .= $this->showComment($comment, $comments);
        }
        $this->html = $this->layout($totalComments, $this->html);
        return $this->html;
    }

    public function outputComment(string $content, ?object $comment, ?string $template = null, ?array $comments = null) : string
    {
        $template = str_replace('{{id}}', (string) $comment->cmt_id ?? '', $template);
        $template = str_replace('{{image}}', !empty($comment->img) ? ImageManager::asset_img(htmlspecialchars($comment->img, ENT_QUOTES)) : ImageManager::asset_img('users' . DS . 'avatar.png') ?? null, $template);
        $template = str_replace('{{name}}', htmlspecialchars($comment->first_name . ' ' . ucfirst(mb_substr($comment->last_name, 0, 1, 'UTF-8')) . '.' ?? '', ENT_QUOTES) ?? null, $template);
        $template = str_replace('{{date}}', $this->timeAgo($comment->created_at) ?? null, $template);
        $template = str_replace('{{comment_content}}', $content . ($comment->approved ? '' : '&nbsp;<i>(Awaiting Approval)</i>'), $template);
        // $template = str_replace('{{votes}}', (string) $comment->votes ?? null, $template);
        // $template = str_replace('{{show_comments}}', $this->showAllComments($comments, $filters, $comment->cmtID) ?? null, $template);
        // $nestedComments = $this->outputNestedComment($comment);
        $template = str_replace('{{nestedComment}}', $this->outputNestedComment($comment), $template);
        return $template;
    }

    public function showComment(?object $comment, ?array $comments = null) : string
    {
        if (null != $comment) {
            $template = $this->template;
            $content = nl2br(htmlspecialchars($comment->content, ENT_QUOTES));
            $content = str_ireplace(['&lt;i&gt;', '&lt;/i&gt', '&lt;b&gt;', '&lt;u&gt;', '&lt;/u&gt;', '&lt;/u&gt;', '&lt;code&gt;', '&lt;.code&gt;', '&lt;pre&gt;', '&lt;/pre&gt;'], ['<i>', '</i>', '<b>', '</b>', '<u>', '</u>', '<code>', '</code>', '<pre>', '</pre>'], $content);
            return $this->outputComment($content, $comment, $template, $comments);
        }
        return '';
    }

    private function outputNestedComment($comment) : string
    {
        $nested = '';
        $children = $this->children($comment);
        if (count($children) > 0) {
            foreach ($children as $comment) {
                $nested = $nested . $this->showComment($comment, $children);
            }
        }
        return $nested;
    }

    private function getChildren(): array
    {
        return $this->children;
    }

    private function children(stdClass $comment) : array
    {
        if (property_exists($comment, 'children')) {
            return $comment->children;
        }
        return [];
    }

    private function count() : int
    {
        return count($this->children);
    }

    private function layout(int $totalComments, string $comments) : string
    {
        $this->layout = str_replace('{{TotalComments}}', strval($totalComments), $this->layout);
        $this->layout = str_replace('{{addCommentForm}}', $this->addCommentForm->createForm('', null) ?? null, $this->layout);
        $this->layout = str_replace('{{replyCommentForm}}', $this->replyCommentForm->createForm('', null) ?? null, $this->layout);
        $this->layout = str_replace('{{commentTemplate}}', $comments, $this->layout);
        $this->layout = str_replace('{{image}}', ImageManager::asset_img('users' . DS . 'avatar.png') ?? null, $this->layout);
        return $this->layout;
    }
}