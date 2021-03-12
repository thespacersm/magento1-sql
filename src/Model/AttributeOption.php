<?php

namespace Magento1Sql\Model;


use Magento1Sql\Magento;
use Magento1Sql\MagentoInterface;

class AttributeOption extends AbstractEntity
{

    public function getId()
    {
        return $this->get('option_id', null);
    }

    public function getValues()
    {
        return $this->get('values', null);
    }

}