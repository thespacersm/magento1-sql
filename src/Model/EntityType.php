<?php


namespace Magento1Sql\Model;


class EntityType extends AbstractEntity
{
    public function getId()
    {
        return $this->get('entity_type_id', null);
    }

    public function getCode()
    {
        return $this->get('entity_type_code', null);
    }
}