<?php

declare(strict_types=1);
class ProductsManager extends Model
{
    protected $_colID = 'pdtID';
    protected $_table = 'products';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getProducts(mixed $brand = 2) : array
    {
        $query_params = $this->table()
            ->leftJoin('product_categorie', ['pdtID', 'catID'])
            ->leftJoin('categories', ['categorie'])
            ->leftJoin('brand', ['br_name'])
            ->on(['pdtID',  'pdtID'], ['catID', 'catID'], ['brID', 'brID'])
            ->where(['brID' => [$brand, 'categories']])
            ->groupBy(['pdtID DESC' => 'product_categorie'])
            ->return('object');
        $pdt = $this->getAll();
        return $pdt->count() > 0 ? $pdt->get_results() : false;
    }
}