<?php

declare(strict_types=1);
class RetrieveCommentsListener implements ListenerInterface
{
    private CommentsInterface $comment;
    private int $limit = 30;

    public function __construct(private CommentsFactory $cmtFactory)
    {
        $this->comment = $cmtFactory->create();
    }

    public function handle(EventsInterface $event): ?iterable
    {
        /** @var CommentsEntity */
        $comment = $this->userComment($event);
        /** @var array */
        $comments = $this->allComments($event);
        $params = $event->getParams();
        return [
            'totalComment' => $this->totalComment($event),
            'userComments' => $comment !== null ? $this->comment->showComment($comment) : null,
            'commentsHtml' => $this->comment->showAllComments($comments, $params['filters'] ?? [], $comment->parent_id ?? 0),
            'comments' => $comments,
        ];
        return null;
    }

    private function allComments(EventsInterface $event) : ?array
    {
        $params = $event->getParams();
        /** @var CommentsManager */
        $object = $event->getObject();
        $object->table()->reset()->where(['page_id' => $this->pageID($params['args'] ?? []), 'approved' => 1])
            ->parameters(['limit' => $this->limit($params['args'] ?? [])])
            ->orderBy($this->sortBy($params['args'] ?? []))->return('object');
        $r = $object->getAll();
        if ($object->count() > 0) {
            return $object->get_results();
        }
        return null;
    }

    private function userComment(EventsInterface $event) : ?CommentsEntity
    {
        /** @var CommentsManager */
        $object = $event->getObject();
        $lastID = $object->getLastID();
        if (null === $lastID) {
            return null;
        }
        $object->table()->where(['cmtID' => $lastID])->reset()->return('object');
        $object->assign((array) $object->getAll()->get_results());
        return $object->getEntity();
    }

    private function totalComment(EventsInterface $event) : ?int
    {
        /** @var CommentsManager */
        $object = $event->getObject();
        $params = $event->getParams();
        $page_id = $this->pageID($params['args'] ?? []);
        if ($page_id === null) {
            return null;
        }
        $object->table(null, ['COUNT|cmtID|total_comments'])->reset()
            ->where(['page_id' => $page_id, 'approved' => 1])->return('object');
        /* @var CommentsManager */
        $r = $object->getAll();
        if ($object->count() > 0) {
            return current($object->get_results())->total_comments;
        }
        return null;
    }

    private function limit(array $params) : int
    {
        if (isset($params['current_pagination_page'])) {
            return (int) $params['current_pagination_page'] * (int) $this->commentPerPaginationPage($params);
        }
        return $this->limit;
    }

    private function pageID(array $params) : ?int
    {
        return isset($args['page_ig']) ? $params['page_id'] : 1;
    }

    private function sortBy(array $params) : array
    {
        if (isset($params['sort_by'])) {
            if ($params['sort_by'] == 'newest') {
                return ['created_at DESC'];
            }
            if ($params['sort_by'] == 'oldest') {
                return ['created_at ASC'];
            } else {
                return ['votes DESC', 'created_at DESC'];
            }
        }
        return [];
    }

    private function commentPerPaginationPage(array $params)
    {
        return isset($params['comments_to_show']) ? $params['comments_to_show'] : 30;
    }
}