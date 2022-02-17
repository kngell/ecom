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

    public function getEntityFields() : array
    {
        $this->getReflect();
        return array_column($this->reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE), 'name');
    }

    public function display(string $field) : string
    {
        $this->getReflect();
        $docs = $this->reflect->getProperty($field)->getDocComment();
        if (!$docs == false) {
            $docs = explode('@', $docs);
            $docs = rtrim($docs[1], ' /**');
            return $docs;
        }
        return '';
    }

    public function populateEntity(array $params) : self
    {
        $this->getReflect();
        $props = $this->getEntityFields();
        foreach ($props as $field) {
            if (in_array($field, array_keys($params))) {
                if (method_exists($this, 'set' . ucwords($field))) {
                    $method = 'set' . ucwords($field);
                    if (isset($params[$field])) {
                        $rp = new ReflectionProperty($this, $field);
                        $type = $rp->getType()->getName();
                        if ($type === 'DateTimeInterface') {
                            $this->$method(new DateTimeImmutable($params[$field]));
                        } elseif ($type === 'string' && is_array($params[$field])) {
                            $this->$method($params[$field][0]);
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

    private function getReflect()
    {
        if (!isset($this->reflect)) {
            return $this->reflect = new ReflectionClass($this);
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
