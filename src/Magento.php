<?php

namespace App;

use App\Model\AbstractEntity;
use App\Model\Attribute;
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
    public function getProductBySku($sku)
    {
        $table = $this->getTable('catalog_product_entity');

        $rows = $this->rawSql->getRows("
        SELECT *
        FROM {$table}
        WHERE sku = {$sku}
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