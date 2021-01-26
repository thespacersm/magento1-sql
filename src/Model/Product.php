<?php

namespace App\Model;


use App\Magento;

class Product extends AbstractEntity
{
    /**
     * @var Magento
     */
    protected $magento;

    /**
     * Product constructor.
     * @param array $data
     * @param Magento $magento
     */
    public function __construct(array $data, Magento $magento)
    {
        $this->magento = $magento;
        parent::__construct($data);
    }

    public function getId()
    {
        return $this->get('entity_id', null);
    }

    public function getSku()
    {
        return $this->get('sku', null);
    }

}