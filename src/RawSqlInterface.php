<?php


namespace Magento1Sql;


use Psr\Log\LoggerInterface;

interface RawSqlInterface
{

    /**
     * @param $sql
     * @return array
     */
    public function getRows($sql);

    /**
     * @param $sql
     * @return LoggerInterface
     */
    public function getLogger($sql);

    /**
     * @param $logger
     * @return RawSqlInterface
     */
    public function setLogger($logger);

}