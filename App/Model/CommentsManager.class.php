<?php

declare(strict_types=1);
class CommentsManager extends Model
{
    protected $_colID = 'cmt_id';
    protected $_table = 'comments';
    private int $start;
    private int $max;
    private int $offset;
    private int $limit;
    private string $parentID = 'parent_id';
    private int $parentIDValue = 0;

    public function __construct(int $start = 0, int $offset = 0, int $limit = 2)
    {
        parent::__construct($this->_table, $this->_colID);
        $this->start = $start;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->max = $this->totalComments();
    }

    public function saveComment() : ?self
    {
        $this->assign([
            'approved' => COMMENT_APPROVAL_REQUIRED ? 0 : 1,
            'user_id' => $this->session->get(CURRENT_USER_SESSION_NAME)['id'],
        ]);
        return $this->save();
    }

    public function getComments()
    {
        if ($this->start > $this->max) {
            return [];
        }
        $comments = [];
        $this->table()->leftJoin('users', ['first_name', 'last_name'])
            ->on(['user_id', 'user_id'])
            ->where(['parent_id' => $this->parentIDValue])
            ->parameters(['limit' => $this->limit, 'offset' => $this->offset])
            ->recursive($this->parentID, $this->_colID)
            ->orderBy(['created_at DESC'])
            ->return('object');
        $this->getAll();
        if ($this->count() > 0) {
            $comments = $this->get_results();
        }
        return $this->container->make(TreeBuilderInterface::class)->buildChildTreeView($comments);
    }

    public function maxComments() : int
    {
        return $this->max;
    }

    public function getVotes(int $id) : ?self
    {
        if (isset($id)) {
            $this->table(null, ['votes'])->where(['cmtID' => $id])->return('object');
            $votes = $this->getAll();
            if ($votes->count() === 1) {
                return $votes->get_results();
            }
        }
        return null;
    }

    /**
     * Set the value of comments.
     *
     * @return  self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    private function totalComments() : int
    {
        $this->table(null, ['COUNT|cmt_id|total'])
            ->where(['parent_id' => $this->parentIDValue])
            ->parameters(['limit' => $this->limit, 'offset' => $this->offset])
            ->orderBy(['created_at DESC'])
            ->recursive($this->parentID, $this->_colID)
            ->return('object');
        $this->getAll();
        if ($this->count() > 0) {
            return (int) current($this->get_results())->total;
        }
        return 0;
    }
}