<?php

declare(strict_types=1);
abstract class AbstractDatatable implements DatatableInterface
{
    protected const TABLE_PROPERTIES = [
        'status' => '',
        'orderby' => '',
        'table_class' => ['table-responsive mt-5'],
        'table_id' => 'datatable',
        'show_table_thead' => true,
        'show_table_tfoot' => false,
        'before' => '<div>',
        'after' => '</div>',
    ];
    protected const COLOMNS_PARTS = [
        'db_row' => '',
        'dt_row' => '',
        'class' => '',
        'show_column' => true,
        'sortable' => false,
        'formatter' => '',
    ];
    protected array $attr = [];

    public function __construct(array $attributes = [])
    {
        if ($attributes) {
            $this->attr = array_merge(self::TABLE_PROPERTIES, $attributes);
        } else {
            $this->attr = self::TABLE_PROPERTIES;
        }
        foreach ($this->attr as $key => $value) {
            if (!$this->isValidAttribute($key, $value)) {
                $this->isValidAttribute($key, self::TABLE_PROPERTIES[$key]);
            }
        }
    }

    public function setAttr(array $attributes = []) : self
    {
        if (is_array($attributes) && count($attributes) > 0) {
            $propKeys = array_keys(self::TABLE_PROPERTIES);
            foreach ($attributes as $key => $value) {
                if (!in_array($key, $propKeys)) {
                    throw new BaseInvalidArgumentException('Invalid property key set.');
                }
                $this->isValidAttribute($key, $value);
                $this->attr[$key] = $value;
            }
        }

        return $this;
    }

    private function isValidAttribute(string $key, $value) : void
    {
        if (empty($key)) {
            throw new BaseInvalidArgumentException('Invalid or empty Attribute key! ' . $value);
        }
        switch ($key) {
            case 'status':
            case 'orderby':
            case 'table_id':
            case 'before':
            case 'after':
                if (!is_string($value)) {
                    throw new BaseInvalidArgumentException('Invalid or empty Attribute type! ' . $value . ' should be a string');
                }
                break;
            case 'show_table_thead':
            case 'show_table_tfoot':
                if (!is_bool($value)) {
                    throw new BaseInvalidArgumentException('Invalid or empty Attribute type! ' . $value . ' should be a Boolean');
                }
                break;
            case 'table_class':
                if (!is_array($value)) {
                    throw new BaseInvalidArgumentException('Invalid or empty Attribute type! ' . $value . ' should be an Array');
                }
                break;
        }
        $this->attr[$key] = $value;
    }
}
