<?php

declare(strict_types=1);
class CommentsController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    public function newComment(array $args = []) : void
    {
        /** @var CommentsManager */
        $model = $this->model(CommentsManager::class)->assign($data = $this->isValidRequest());
        $this->isIncommingDataValid(m: $model, ruleMethod:'comments', newKeys: [
            'content' => $data['content_id'],
        ]);
        if (!AuthManager::isLoggedIn()) {
            $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Please login to comment or reply!')]);
        }
        if ($resp = $model->saveComment()) {
            $commentObject = $model->getEntity()->getObject();
            $commentObject->last_name = $this->session->get(CURRENT_USER_SESSION_NAME)['last_name'];
            $commentObject->first_name = $this->session->get(CURRENT_USER_SESSION_NAME)['first_name'];
            $commentObject->created_at = (new DateTimeImmutable())->format('Y-m-d H:i:s');
            $commentObject->cmt_id = $resp->getLastID();
            $comment = $this->comment->showComment($commentObject);
            $maxCount = isset($data['max']) ? (int) $data['max'] + 1 : 0;
            $this->jsonResponse(['result' => 'success', 'msg' => ['comment' => $comment, 'nbCmt' => $maxCount]]);
        }
    }

    protected function sortComment()
    {
    }

    protected function votes(array $args = []) : void
    {
        /** @var CommentsManager */
        $model = $this->model(CommentsManager::class)->assign($this->isPostRequest());
        if (isset($args['comment_id'],$agrs['votes'])) {
            if (!$this->cookie->exists('vote_' . $args['comment_id'])) {
                $resp = $model->assign([
                    'votes' => (int) ($args['vote'] == 'up' ? '+' : '-') . 1,
                    'cmtID' => (int) $args['comment_id'],
                ])->save();
                $this->cookie->set(time() + (10 * 365 * 24 * 60 * 60), 'vote_' . $args['comment_id'], true);
                $this->dispatcher->dispatch(new NewCommentVoteEvent($resp));
            }
            $votes = $model->getVotes($args['comment_id']);
            exit($votes->votes);
        }
    }
}