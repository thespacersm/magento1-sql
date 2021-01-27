<?php


namespace App;


use Psr\Log\LoggerInterface;

class RawSql implements RawSqlInterface
{

    protected $connection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * RawSql constructor.
     */
    public function __construct($host, $dbUser, $dbPassword, $dbName, $port = null)
    {
        $conn = new \mysqli($host, $dbUser, $dbPassword, $dbName, $port);
        $this->connection = $conn;
    }

    public function getRows($sql)
    {
        $rows = [];

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * @inheritDoc
     */
    public function getLogger($sql)
    {
        return $this->logger;
    }


    /**
     * @param LoggerInterface $logger
     * @return RawSql
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    protected function query($sql)
    {
        if ($this->logger) {
            $this->logger->info('QUERY', [
                'sql' => $sql,
            ]);
        }
        $r = $this->connection->query($sql);
        if (!$r) {
            throw new \Exception(mysqli_error($this->connection));
        }
        return $r;
    }

}