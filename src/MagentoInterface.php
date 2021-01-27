<?php


namespace App;


use App\Model\AbstractEntity;
use App\Model\Attribute;
use App\Model\Category;
use App\Model\EntityType;
use App\Model\Product;

interface MagentoInterface
{

    /**
     * @param $table
     * @return int
     */
    public function getCount($table);

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getProducts($offset, $limit);

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getCategories($offset, $limit);

    /**
     * @param $id
     * @return Product
     */
    public function getProductById($id);

    /**
     * @param $id
     * @return Category
     */
    public function getCategoryById($id);

    /**
     * @param $sku
     * @return Product
     */
    public function getProductBySku($sku);

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getUrlRewrites($offset, $limit);

    /**
     * @param $id
     * @return array
     */
    public function getUrlRewritesByProductId($id);

    /**
     * @param $id
     * @return array
     */
    public function getUrlRewritesByCategoryId($id);

    /**
     * @param $requestPath
     * @return array
     */
    public function getUrlRewritesByRequestPath($requestPath);

    /**
     * @param $entityId
     * @param $entityTypeCode
     * @param $attributeCode
     * @param $storeId
     * @return mixed
     */
    public function getEavAttributeValue($entityId, $entityTypeCode, $attributeCode, $storeId = 0);

    /**
     * @param $attributeValue
     * @param $attributeCode
     * @param $entityTypeCode
     * @param int $storeId
     * @return AbstractEntity
     */
    public function getEntityByEavAttributeValue($attributeValue, $attributeCode, $entityTypeCode, $storeId = 0);

    /**
     * @return array
     */
    public function getEavAttributes();

    /**
     * @return array
     */
    public function getEavEntityTypes();

    /**
     * @param $id
     * @return EntityType
     */
    public function getEavEntityTypeById($id);

    /**
     * @param $code
     * @return EntityType
     */
    public function getEavEntityTypeByCode($code);

    /**
     * @param $id
     * @return Attribute
     */
    public function getEavAttributeById($id);

    /**
     * @param $code
     * @param int $entityTypeId
     * @return Attribute
     */
    public function getEavAttributeByCode($code, $entityTypeId);

    /**
     * @param $name
     * @return string
     */
    public function getTable($name);

    /**
     * @return string|null
     */
    public function getTablePrefix();

    /**
     * @param string|null $tablePrefix
     * @return Magento
     */
    public function setTablePrefix($tablePrefix);
}