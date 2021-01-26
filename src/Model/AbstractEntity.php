<?php

namespace App\Model;

abstract class AbstractEntity
{
    /**
     * @var array
     */
    protected $data;

    /**
     * AbstractEntity constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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