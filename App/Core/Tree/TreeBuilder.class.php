<?php

declare(strict_types=1);

class TreeBuilder implements TreeBuilderInterface
{
    private string $parentIdColName;
    private string $idColName;
    private string $childrenKeyName;

    public function __construct(private array $items = [], string $parentIdColName = 'parent_id', string $idColName = 'cmt_id', string $childrenKeyName = 'children')
    {
        $this->parentIdColName = $parentIdColName;
        $this->idColName = $idColName;
        $this->childrenKeyName = $childrenKeyName;
    }

    public function buildSChildStandardTreeView(array $items, int $parentID = 0): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ((string) $item->{$this->parentIdColName} === (string) $parentID) {
                $children = $this->buildSChildStandardTreeView($items, $item->{$this->idColName});
                if ($children) {
                    $item->{$this->childrenKeyName} = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }

    public function buildChildTreeView(array $items) : array
    {
        $tree = [];
        while (!empty($items)) {
            $item = array_shift($items);
            $this->buildBranch($item, $items);
            $tree[] = $item;
        }
        return $tree;
    }

    private function buildBranch(stdClass &$branch, array &$items, ?stdClass &$parent = null) : array
    {
        $hitBottom = false;
        while (!$hitBottom) {
            list($removals, $foundSomething, $items) = $this->foundChildren($items, $branch);
            foreach ($removals as $idx) {
                unset($items[$idx]);
            }
            if ($parent === null) {
                list($items, $foundParent, $foundSomething) = $this->foundParents($items, $branch, $foundSomething);
            }
            return [$hitBottom = !$foundSomething, $items];
        }
    }

    private function foundParents(array $items, stdClass $branch, bool $foundSomeThing) : array
    {
        $foundParent = false;
        foreach ($items as $idx => $item) {
            if ($item->{$this->idColName} === $branch->{$this->parentIdColName}) {
                $foundSomeThing = true;
                $foundParent = true;
                $this->addChild($item, $branch);
                unset($items[$idx]);
                $branch = $item;
                break;
            }
        }
        return [$items, $foundParent, $foundSomeThing];
    }

    private function foundChildren($items, stdClass $branch) : array
    {
        $removals = [];
        $foundsomething = false;
        foreach ($items as $idx => $item) {
            if ($item->{$this->parentIdColName} === $branch->{$this->idColName}) {
                $foundsomething = true;
                $this->buildBranch($item, $items, $branch);
                $this->addChild($branch, $item);
                $removals[] = $idx;
            }
        }
        return [$removals, $foundsomething, $items];
    }

    private function addChild(stdClass $parent, stdClass $child)
    {
        if ($child->{$this->parentIdColName} != $parent->{$this->idColName}) {
            throw new BuildTreeException('Attempting to add child to wrong parent');
        }
        if (empty($parent->{$this->childrenKeyName})) {
            $parent->{$this->childrenKeyName} = [];
        } else {
            foreach ($parent->{$this->childrenKeyName} as $idx => $chd) {
                if ($chd->{$this->idColName} === $child->{$this->idColName}) {
                    if (empty($chd->{$this->childrenKeyName})) {
                        $parent->{$this->childrenKeyName}[$idx] = $child;
                        return;
                    } else {
                        if (empty($child->{$this->childrenKeyName})) {
                            return;
                        } else {
                            $chd->{$this->childrenKeyName} += $child->{$this->childrenKeyName};
                            $parent->{$this->childrenKeyName}[$idx] = $child;
                            return;
                        }
                    }
                }
            }
        }
        $parent->{$this->childrenKeyName}[] = $child;
    }
}