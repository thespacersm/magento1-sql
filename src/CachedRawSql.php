<?php


namespace Magento1Sql;


use Phpfastcache\CacheManager;
use Phpfastcache\Config\Config;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class CachedRawSql extends RawSql implements RawSqlInterface
{

    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ExtendedCacheItemPoolInterface
     */
    protected $cacheInstance;

    /**
     * @var string
     */
    protected $cacheBaseKey;

    /**
     * RawSql constructor.
     */
    public function __construct($cacheDir, $host, $dbUser, $dbPassword, $dbName, $port = null)
    {
        $conn = new \mysqli($host, $dbUser, $dbPassword, $dbName, $port);
        $this->connection = $conn;

        $cacheInstance = CacheManager::getInstance('files', new Config([
            'path'             => $cacheDir,
            "itemDetailedDate" => false,
        ]));
        $this->cacheInstance = $cacheInstance;

        $this->cacheBaseKey = sprintf("%s-%s-%s", $host, $dbUser, $dbName);
    }

    public function getRows($sql)
    {
        $key = $this->getKey($sql);

        $cachedItem = $this->cacheInstance->getItem($key);
        if (is_null($cachedItem->get())) {

            if ($this->logger) {
                $this->logger->info('CACHE MISS', [
                    'sql' => $sql,
                ]);
            }
            $value = parent::getRows($sql);

            $cachedItem
                ->set(json_encode($value))
                ->expiresAfter(60 * 60 * 24 * 30);

            $this->cacheInstance->save($cachedItem);
        } else {
            if ($this->logger) {
                $this->logger->info('CACHE HIT', [
                    'sql' => $sql,
                ]);
            }
            $value = json_decode($cachedItem->get(), true);
        }

        return $value;
    }

    protected function getKey($sql)
    {
        return md5(implode("-", [
            $this->cacheBaseKey,
            $sql,
        ]));
    }


}