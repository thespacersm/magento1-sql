<?php

namespace App;

use App\Model\AbstractEntity;
use App\Model\Attribute;
use App\Model\Category;
use App\Model\EntityType;
use App\Model\Product;
use App\Model\UrlRewrite;

class Magento implements MagentoInterface
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

    /**
     * @inheritDoc
     */
    public function getCount($table)
    {
        $table = $this->getTable($table);
        $rows = $this->rawSql->getRows("
        SELECT COUNT(*) AS c
        FROM {$table}
        ;");
        $count = 0;
        foreach ($rows as $row) {
            $count = $row['c'];
        }
        return $count;
    }


    /**
     * @param $offset
     * @param $limit
     * @return array
     */
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
            $product = new Product($row, $this);
            $products[] = $product;
        }

        return $products;
    }

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getCategories($offset, $limit)
    {
        $table = $this->getTable('catalog_category_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        LIMIT {$limit}
        OFFSET {$offset}
        ;");

        $categories = [];
        foreach ($rows as $row) {
            $category = new Category($row, $this);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @inheritDoc
     */
    public function getProductById($id)
    {
        $table = $this->getTable('catalog_product_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE entity_id = {$id}
        ;");

        $product = null;
        foreach ($rows as $row) {
            $product = new Product($row, $this);
        }

        return $product;
    }

    /**
     * @inheritDoc
     */
    public function getCategoryById($id)
    {
        $table = $this->getTable('catalog_category_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE entity_id = {$id}
        ;");

        $category = null;
        foreach ($rows as $row) {
            $category = new Category($row, $this);
        }

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function getUrlRewritesByProductId($id)
    {
        $table = $this->getTable('core_url_rewrite');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE product_id = {$id}
        ;");

        $urlRewrites = [];
        foreach ($rows as $row) {
            $urlRewrite = new UrlRewrite($row, $this);
            $urlRewrites[] = $urlRewrite;
        }

        return $urlRewrites;
    }

    /**
     * @inheritDoc
     */
    public function getUrlRewritesByRequestPath($requestPath)
    {
        $table = $this->getTable('core_url_rewrite');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE request_path = '{$requestPath}'
        ;");

        $urlRewrites = [];
        foreach ($rows as $row) {
            $urlRewrite = new UrlRewrite($row, $this);
            $urlRewrites[] = $urlRewrite;
        }

        return $urlRewrites;
    }

    /**
     * @inheritDoc
     */
    public function getUrlRewritesByCategoryId($id)
    {
        $table = $this->getTable('core_url_rewrite');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE category_id = {$id}
        ;");

        $urlRewrites = [];
        foreach ($rows as $row) {
            $urlRewrite = new UrlRewrite($row, $this);
            $urlRewrites[] = $urlRewrite;
        }

        return $urlRewrites;
    }


    /**
     * @inheritDoc
     */
    public function getProductBySku($sku)
    {
        $table = $this->getTable('catalog_product_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE sku = '{$sku}'
        ;");

        $product = null;
        foreach ($rows as $row) {
            $product = new Product($row, $this);
        }

        return $product;
    }


    /**
     * @return array
     */
    public function getEavAttributes()
    {
        $table = $this->getTable('eav_attribute');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        ;");

        $attributes = [];
        foreach ($rows as $row) {
            $attribute = new Attribute($row, $this);
            $attributes[] = $attribute;
        }

        return $attributes;
    }

    /**
     * @inheritDoc
     */
    public function getUrlRewrites($offset, $limit)
    {
        $table = $this->getTable('core_url_rewrite');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        LIMIT {$limit}
        OFFSET {$offset}
        ;");

        $urlRewrites = [];
        foreach ($rows as $row) {
            $urlRewrite = new UrlRewrite($row, $this);
            $urlRewrites[] = $urlRewrite;
        }

        return $urlRewrites;
    }


    /**
     * @inheritDoc
     */
    public function getEavAttributeValue($entityId, $entityTypeCode, $attributeCode, $storeId = 0)
    {
        $entityType = $this->getEavEntityTypeByCode($entityTypeCode);
        $entityTypeId = $entityType->getId();
        $attribute = $this->getEavAttributeByCode($attributeCode, $entityTypeId);
        $attributeId = $attribute->getId();
        $backendType = $attribute->getBackendType();

        $value = null;
        switch ($backendType) {
            case "static":

                break;
            default:

                $table = $this->getTable(sprintf("%s_entity_%s", $entityTypeCode, $backendType));
                $rows = $this->rawSql->getRows("
                SELECT *
                FROM {$table}
                WHERE entity_id = {$entityId}
                AND attribute_id = {$attributeId}
                AND store_id = {$storeId}
                ;");

                if (count($rows)) {
                    $value = $rows[0]['value'];
                }

                break;
        }
        return $value;
    }


    /**
     * @inheritDoc
     */
    public function getEntityByEavAttributeValue($attributeValue, $attributeCode, $entityTypeCode, $storeId = 0)
    {
        $entityType = $this->getEavEntityTypeByCode($entityTypeCode);
        $entityTypeId = $entityType->getId();
        $attribute = $this->getEavAttributeByCode($attributeCode, $entityTypeId);
        $attributeId = $attribute->getId();
        $backendType = $attribute->getBackendType();

        $obj = null;
        switch ($backendType) {
            case "static":

                break;
            default:

                $table = $this->getTable(sprintf("%s_entity_%s", $entityTypeCode, $backendType));
                $rows = $this->rawSql->getRows("
                SELECT *
                FROM {$table}
                WHERE attribute_id = {$attributeId}
                AND store_id = {$storeId}
                AND value = \"{$attributeValue}\"
                ;");

                if (count($rows)) {
                    $id = $rows[0]['entity_id'];

                    switch ($entityTypeCode) {
                        case "catalog_product":
                            $obj = $this->getProductById($id);
                            break;
                        case "catalog_category":
                            $obj = $this->getCategoryById($id);
                            break;
                    }
                }

                break;
        }
        return $obj;
    }

    /**
     * @inheritDoc
     */
    public function getEavEntityTypes()
    {
        $table = $this->getTable('eav_entity_type');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        ;");

        $entityTypes = [];
        foreach ($rows as $row) {
            $entityType = new EntityType($row, $this);
            $entityTypes[] = $entityType;
        }

        return $entityTypes;
    }

    /**
     * @inheritDoc
     */
    public function getEavEntityTypeById($id)
    {
        /** @var EntityType $entityType */
        foreach ($this->getEavEntityTypes() as $entityType) {
            if ($entityType->getId() == $id) {
                return $entityType;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getEavEntityTypeByCode($code)
    {
        /** @var EntityType $entityType */
        foreach ($this->getEavEntityTypes() as $entityType) {
            if ($entityType->getCode() == $code) {
                return $entityType;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getEavAttributeById($id)
    {
        /** @var Attribute $attribute */
        foreach ($this->getEavAttributes() as $attribute) {
            if ($attribute->getId() == $id) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getEavAttributeByCode($code, $entityTypeId)
    {
        /** @var Attribute $attribute */
        foreach ($this->getEavAttributes() as $attribute) {
            if (
                $attribute->getAttributeCode() == $code
                && $attribute->getEntityTypeId() == $entityTypeId
            ) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param $name
     * @return string
     */
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