<?php

namespace Magento1Sql\Model;


use Magento1Sql\Magento;
use Magento1Sql\MagentoInterface;

class Attribute extends AbstractEntity
{

    public function getId()
    {
        return $this->get('attribute_id', null);
    }

    public function getEntityTypeId()
    {
        return $this->get('entity_type_id', null);
    }

    public function getAttributeCode()
    {
        return $this->get('attribute_code', null);
    }

    public function getBackendType()
    {
        return $this->get('backend_type', null);
    }

    public function getFrontendInput()
    {
        return $this->get('frontend_input', null);
    }

}