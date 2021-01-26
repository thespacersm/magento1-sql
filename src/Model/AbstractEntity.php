<?php

namespace App\Model;

use App\Magento;
use App\MagentoInterface;

abstract class AbstractEntity
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var MagentoInterface
     */
    protected $magento;

    /**
     * AbstractEntity constructor.
     * @param array $data
     * @param MagentoInterface $magento
     */
    public function __construct(array $data, MagentoInterface $magento)
    {
        $this->data = $data;
        $this->magento = $magento;
    }


    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function set($key, $value)
    {
        return $this->data[$key] = $value;
    }
}