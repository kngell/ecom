<?php

declare(strict_types=1);

class MenuItemModel extends Model
{
    /** @var string */
    protected const TABLESCHEMA = 'menu_items';
    /** @var string */
    protected const TABLESCHEMAID = 'id';
    /** @var array */
    protected const COLUMN_STATUS = [];

    /**
     * Main constructor class which passes the relevant information to the
     * base model parent constructor. This allows the repository to fetch the
     * correct information from the database based on the model/entity.
     *
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct(self::TABLESCHEMA, self::TABLESCHEMAID, MenuItemEntity::class);
    }

    /**
     * Guard these IDs from being deleted etc..
     *
     * @return array
     */
    public function guardedID(): array
    {
        return [
        ];
    }

    /**
     * Return an array of column values if table supports the column field.
     *
     * @return array
     */
    public function getColumnStatus(): array
    {
        return self::COLUMN_STATUS;
    }
}