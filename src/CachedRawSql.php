<?php


namespace App;


use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;

class CachedRawSql extends RawSql implements RawSqlInterface
{

    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var FilesystemCachePool
     */
    protected $cachePool;

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
        $filesystemAdapter = new Local($cacheDir);
        $filesystem = new Filesystem($filesystemAdapter);
        $pool = new FilesystemCachePool($filesystem);
        $this->cachePool = $pool;
        $this->cacheBaseKey = sprintf("%s-%s-%s", $host, $dbUser, $dbName);
    }

    public function getRows($sql)
    {
        $key = $this->getKey($sql);
        $item = $this->cachePool->getItem($key);
        if (!$item->isHit() || empty($item->get())) {

            $value = parent::getRows($sql);
            $item->set(json_encode($value));

            $d1 = new \DateTime();
            $d2 = new \DateTime();
            $d2->modify('+30 days');
            $diff = $d2->diff($d1);

            $item->expiresAfter($diff);
            $this->cachePool->save($item);
        } else {
            $value = json_decode($item->get(), true);
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