<?php


namespace App;


use App\Model\AbstractEntity;
use App\Model\Attribute;
use App\Model\EntityType;
use App\Model\Product;

interface MagentoInterface
{

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getProducts($offset, $limit);

    /**
     * @param $id
     * @param $entityTypeId
     * @return AbstractEntity
     */
    public function getEntityById($id, $entityTypeId);

    /**
     * @param $entityId
     * @param $entityTypeCode
     * @param $attributeCode
     * @param $storeId
     * @return mixed
     */
    public function getEavAttributeValue($entityId, $entityTypeCode, $attributeCode, $storeId = 0);

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