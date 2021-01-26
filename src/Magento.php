<?php

namespace App;

use App\Model\Product;

class Magento
{
    /**
     * @var RawSql
     */
    protected $rawSql;

    protected $tablePrefix;

    /**
     * Magento constructor.
     * @param $rawSql
     */
    public function __construct($rawSql)
    {
        $this->rawSql = $rawSql;
    }

    public function getProducts($offset, $limit)
    {
        $table = $this->getTable('catalog_product_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        LIMIT {$limit}
        OFFSET {$offset}
        ;");

        $products = [];
        foreach ($rows as $row) {
            $product = new Product($row);
            $products[] = $product;
        }

        return $products;
    }

    public function getTable($name)
    {
        return sprintf("%s%s", $this->getTablePrefix(), $name);
    }

    /**
     * @return mixed
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @param mixed $tablePrefix
     * @return Magento
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }


}