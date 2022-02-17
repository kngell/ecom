<?php

declare(strict_types=1);

class Datatable extends AbstractDatatable
{
    protected string $element = '';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function create(string $datacolumnString, array $datarepository, array $sortcontrollerArg) : self
    {
        $this->dataColumnsObject = Container::getInstance()->make($datacolumnString);
        if (!$this->dataColumnsObject instanceof DatatableColumnInterface) {
            throw new BaseUnexpectedValueException($datacolumnString . ' is not an valid Datatable object!');
        }
        $this->dataColumns = $this->dataColumnsObject->columns();
        $this->sortController = $sortcontrollerArg;
        $this->getRepositoryParts($datarepository);
        return $this;
    }

    public function table(): ?string
    {
        extract($this->attr);
        $this->element .= $before;
        if (is_array($this->dataColumns) && count($this->dataColumns) > 0) {
            if (is_array($this->dataOptions) && $this->dataOptions != null) {
                $this->element .= '<table id="' . (isset($table_id) ? $table_id : '') . '" class="' . implode(' ', $table_class) . '">' . "\n";
                $this->element .= ($show_table_thead) ? $this->tableGridElements($status) : false;
                $this->element .= '<tbody>';
                foreach ($this->dataOptions as $row) {
                    $this->element .= '<tr>';
                    foreach ($this->dataColumns as $column) {
                        if (isset($column['show_column']) && $column['show_column'] != false) {
                            $this->element .= '<td class="' . $column['class'] . '">';
                            if (is_callable($column['formatter'])) {
                                $this->element .= call_user_func_array($column['formatter'], [$row]);
                            } else {
                                $this->element .= (isset($row[$column['db_row']])) ? $row[$column['db_row']] : '';
                            }
                            $this->element .= '</td>';
                        }
                    }
                    $this->element .= '</tr>';
                }
                $this->element .= '</tbody>';
                $this->element .= ($show_table_tfoot) ? $this->tableGridElements($status, true) : '';
                $this->element .= '</table>';
            }
            $this->element .= $after;
        }
        return $this->element;
    }

    public function pagination() : string
    {
        extract($this->attr, EXTR_SKIP);

        $element = '<section class="uk-margin-medium-top uk-padding-small uk-padding-remove-bottom">';
        $element .= '<nav aria-label="Pagination" uk-navbar>';
        /*
         * table meta information
         */
        $element .= '<div class="uk-navbar-left" style="margin-top: -15px;">';
        $element .= sprintf('&nbsp;Showing&nbsp<span>%s</span> - <span>%s</span>&nbsp; of &nbsp;<span>%s</span>&nbsp;', $this->currentPage, $this->totalPages, $this->totalRecords);
        $element .= '<span class="uk-text-meta uk-text-warning uk-margin-small-left"></span>';
        $element .= '</div>';
        $queryStatus = ($this->sortController['query'] ? $this->sortController['query'] : '');
        $status = (isset($_GET[$queryStatus]) ? $_GET[$queryStatus] : '');
        /*
         * pagination simple or numbers
         */
        $element .= '<div class="uk-navbar-right">';
        $element .= '<ul class="uk-pagination">';
        $element .= '<li class="' . ($this->currentPage == 1 ? 'uk-disabled' : 'uk-active') . '">';
        if ($this->currentPage == 1) {
            $element .= sprintf('<a href="%s"><span class="uk-margin-small-right" uk-pagination-previous></span> Previous</a>', 'javascript:void(0);');
        } else {
            if ($status) {
                $element .= sprintf('<a href="?' . $queryStatus . '=%s&page=%s">', $status, ($this->currentPage - 1));
            } else {
                $element .= sprintf('<a href="?page=%s">', ($this->currentPage - 1));
            }
            $element .= '<span class="uk-margin-small-right" uk-pagination-previous></span> Previous</a>';
        }
        $element .= '</li>';
        /* NEXT */
        $element .= '<li class="uk-margin-auto-left ' . ($this->currentPage == $this->totalPages ? 'uk-disabled' : 'uk-active') . '">';
        if ($this->currentPage == $this->totalPages) {
            $element .= sprintf('<a href="%s">Next <span class="uk-margin-small-left" uk-pagination-next></span>', 'javascript:void(0);');
        } else {
            if ($status) {
                $element .= sprintf('<a href="?' . $queryStatus . '=%s&page=%s">', $status, ($this->currentPage + 1));
            } else {
                $element .= sprintf('<a href="?page=%s">', ($this->currentPage + 1));
            }
            $element .= 'Next <span class="uk-margin-small-left" uk-pagination-next></span></a>';
        }
        $element .= '</li>';
        $element .= '</ul>';
        $element .= '</div>';
        $element .= '</nav>';
        $element .= '</section>';
        return $element;
    }

    private function getRepositoryParts(array $datarepository) : void
    {
        list($this->dataOptions, $this->currentPage, $this->totalPages, $this->totalRecords, $this->direction, $this->sortDirection, $this->tdClass, $this->tableColumn, $this->tableOrder) = $datarepository;
    }

    private function tableGridElements(string $status, bool $inFoot = false) : string
    {
        $element = sprintf('<%s>', ($inFoot) ? 'tfoot' : 'thead');
        $element .= '<tr>';
        foreach ($this->dataColumns as $column) {
            if (isset($column['show_column']) && $column['show_column'] != false) {
                $element .= '<th>';
                $element .= $this->tableSorting($column, $status);
                $element .= '</th>';
            }
        }
        $element .= '</tr>';
        $element .= sprintf('</%s>', $inFoot ? 'tfoot' : 'thead');
        return $element;
    }

    private function tableSorting(array $column, string $status) : string
    {
        $element = '';
        if (isset($column['sortable']) && $column['sortable'] != false) {
            $element .= '<a class="uk-link-reset" href="' . ($status ? '?status=' . $status . '&column=' . $column['db_row'] . '&order=' . $this->sortDirection . '' : '?column=' . $column['db_row'] . '&order=' . $this->sortDirection . '') . '">';
            $element .= $column['dt_row'];
            $element .= '<i class="fas fa-sort' . ($this->tableColumn == $column['db_row'] ? '-' . $this->direction : '') . '"></i>';
            $element .= '</a>';
        } else {
            $element .= $column['dt_row'];
        }
        return $element;
    }
}
