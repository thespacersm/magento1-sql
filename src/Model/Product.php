<?php

namespace Magento1Sql\Model;


use Magento1Sql\Magento;
use Magento1Sql\MagentoInterface;

class Product extends AbstractEntity
{

    public function getId()
    {
        return $this->get('entity_id', null);
    }

    public function getSku()
    {
        return $this->get('sku', null);
    }

    public function getAttributeValue($attributeCode, $storeId = 0)
    {
        return $this->magento->getEavAttributeValue(
            $this->getId(),
            'catalog_product',
            $attributeCode,
            $storeId
        );
    }

}