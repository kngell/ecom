<?php

declare(strict_types=1);

class Entity extends AbstractEntity
{
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
                    $properties[$this->getOriginalField($field)] = $rp->getValue($this)->format('Y-m-d H:i:s');
                } else {
                    $properties[$this->getOriginalField($field)] = $rp->getValue($this);
                }
            }
        }
        return $properties;
    }

    public function getFields(string $field) : string
    {
        return $this->reflectionInstance()->getProperty($field)->getName();
    }

    public function getFieldWithDoc(string $withDocComment) : string
    {
        return $this->getColID($withDocComment);
    }

    public function getColId(string $withDocComment = 'id') :  string
    {
        $props = $this->getAllAttributes();
        foreach ($props as $field) {
            $docs = $this->getPropertyComment($field);
            if ($docs == $withDocComment) {
                return $this->getOriginalField($field);
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

    public function getObject() : object
    {
        return (object) $this->getInitializedAttributes();
    }

    public function assign(array $params) : self
    {
        foreach ($params as $field => $value) {
            if (is_string($field)) {
                $prop = $this->regenerateField($field);
                $method = $this->getSetter($field);
                if (method_exists($this, $method)) {
                    $rp = $this->reflectionInstance()->getProperty($prop);
                    $type = $rp->getType()->getName();
                    $result = match ($type) {
                        'DateTimeInterface' => $this->$method(new DateTimeImmutable($value)),
                            'string' => is_array($value) ? $this->$method((string) $value[0]) : $this->$method((string) $value),
                            'int' => $this->$method((int) $value),
                            default => $this->$method($value)
                    };
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

    public function delete(?string $field = null) : self
    {
        $reflectionProperty = $this->reflectionInstance()->getProperty($field);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this, null);
        return $this;
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