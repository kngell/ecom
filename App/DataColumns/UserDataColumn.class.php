<?php

declare(strict_types=1);
class UserDataColumn extends AbstractDatatableColumns
{
    private string $_table = 'users';

    public function columns() : array
    {
        /** @var Entity */
        $entity = Container::getInstance()->make(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table))) . 'Entity');
        $fields = $entity->getEntityFields();
        $columns = [];
        foreach ($fields as $field) {
            $columns[] = [
            'db_row' => $field,
            'dt_row' => $entity->display($field),
            'class' => '',
            'show_column' => true,
            'sortable' => false,
            'formatter' => '',
           ];
        }
        return[
            [
                'db_row' => 'userID',
                'dt_row' => 'ID',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'firstName',
                'dt_row' => 'FirstName',
                'class' => 'flex-shrink-0 me-3',
                'show_column' => true,
                'sortable' => true,
                'formatter' => '',
            ],
            [
                'db_row' => 'lastName',
                'dt_row' => 'lastName',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'email',
                'dt_row' => 'Email Address',
                'class' => '',
                'show_column' => true,
                'sortable' => true,
                'formatter' => '',
            ],
            [
                'db_row' => 'verified',
                'dt_row' => 'Status',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'registerDate',
                'dt_row' => 'Published',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'updateAt',
                'dt_row' => 'Modified',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'profileImage',
                'dt_row' => 'Thuamb',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => 'deleted',
                'dt_row' => 'deleted',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
            [
                'db_row' => '',
                'dt_row' => 'Action',
                'class' => '',
                'show_column' => true,
                'sortable' => false,
                'formatter' => '',
            ],
        ];
    }
}
