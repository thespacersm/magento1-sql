<?php

namespace App\Model;


use App\Magento;
use App\MagentoInterface;

class UrlRewrite extends AbstractEntity
{

    public function getId()
    {
        return $this->get('url_rewrite_id', null);
    }

    public function getStoreId()
    {
        return $this->get('store_id', null);
    }

    public function getIdPath()
    {
        return $this->get('id_path', null);
    }

    public function getRequestPath()
    {
        return $this->get('request_path', null);
    }

    public function getTargetPath()
    {
        return $this->get('target_path', null);
    }

    public function getIsSystem()
    {
        return $this->get('is_system', null);
    }

    public function getOptions()
    {
        return $this->get('options', null);
    }

    public function getDescription()
    {
        return $this->get('description', null);
    }

    public function getCategoryId()
    {
        return $this->get('category_id', null);
    }

    public function getProductId()
    {
        return $this->get('product_id', null);
    }


}