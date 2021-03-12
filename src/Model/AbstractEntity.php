<?php

namespace Magento1Sql\Model;

use Magento1Sql\Magento;
use Magento1Sql\MagentoInterface;

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