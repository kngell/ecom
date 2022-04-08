<?php

declare(strict_types=1);

abstract class Entity
{
    private ReflectionClass $reflect;

    /**
     * Sanitize
     * =========================================================.
     * @param array $dirtyData
     */
    public function sanitize(array $dirtyData)
    {
        if (empty($dirtyData)) {
            throw new BaseInvalidArgumentException('No data was submitted');
        }
        if (is_array($dirtyData)) {
            foreach ($this->cleanData($dirtyData) as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getAllAttributes() : array
    {
        return array_column($this->reflectionInstance()->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE), 'name');
    }

    public function getInitializedAttributes() : array
    {
        $properties = [];
        foreach ($this->getAllAttributes() as $field) {
            $rp = $this->reflectionInstance()->getProperty($field);
            if ($rp->isInitialized($this)) {
                if ($rp->getType()->getName() === 'DateTimeInterface') {
                    $properties[$field] = $rp->getValue($this)->format('Y-m-d H:i:s');
                } else {
                    $properties[$field] = $rp->getValue($this);
                }
            }
        }
        return $properties;
    }

    public function getFields(string $field) : string
    {
        return $this->reflectionInstance()->getProperty($field)->getName();
    }

    public function getColId() :  string
    {
        $props = $this->getAllAttributes();
        foreach ($props as $field) {
            $docs = $this->getPropertyComment($field);
            if ($docs == 'id') {
                return $field;
                exit;
            }
        }
        return '';
    }

    public function getPropertyComment(string $field) : string
    {
        $propertyComment = $this->reflectionInstance()->getProperty($field)->getDocComment();
        return $this->filterPropertyComment($propertyComment);
    }

    public function assign(array $params) : self
    {
        $props = $this->getAllAttributes();
        foreach ($props as $field) {
            if (in_array($field, array_keys($params))) {
                if (method_exists($this, 'set' . ucwords($field))) {
                    $method = 'set' . ucwords($field);
                    if (isset($params[$field])) {
                        $rp = $this->reflectionInstance()->getProperty($field); // new ReflectionProperty($this, $field);
                        $type = $rp->getType()->getName();
                        if ($type === 'DateTimeInterface') {
                            $this->$method(new DateTimeImmutable($params[$field]));
                        } elseif ($type === 'string') {
                            is_array($params[$field]) ? $this->$method((string) $params[$field][0]) : $this->$method((string) $params[$field]);
                        } else {
                            $this->$method($params[$field]);
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Get Html Decode texte
     * ====================================================================================.
     * @param string $str
     * @return string
     */
    public function htmlDecode(?string $str) : ?string
    {
        return !empty($str) ? htmlspecialchars_decode(html_entity_decode($str), ENT_QUOTES) : '';
    }

    public function getContentOverview(string $content):string
    {
        // $headercontent = preg_match_all('|<h[^>]+>(.*)</h[^>]+>|iU', htmlspecialchars_decode($content, ENT_NOQUOTES), $headings);
        return substr(strip_tags($this->htmlDecode($content)), 0, 200) . '...';
    }

    private function filterPropertyComment(false|string $comment) : string
    {
        if (is_string($comment)) {
            preg_match('/@(?<content>.+)/i', $comment, $content);
            $content = isset($content['content']) ? $content['content'] : '';
            return trim(str_replace('*/', '', $content));
        }
        return '';
    }

    private function reflectionInstance()
    {
        if (!isset($this->reflect)) {
            return $this->reflect = new ReflectionClass($this::class);
        }
        return $this->reflect;
    }

    /**
     * Clean Data
     * ==========================================================.
     * @param array $dirtyData
     * @return array
     */
    private function cleanData(array $dirtyData) : array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if ($cleanData) {
            return $cleanData;
        }
        return [];
    }
}