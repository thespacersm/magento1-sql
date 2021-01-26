<?php


namespace App;


class RawSql
{

    protected $connection;

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

        $result = $this->connection->query($sql);

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


}