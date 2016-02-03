<?php
namespace Service;
use PDO;

class PdoSqlite
{
    /** @var PDO */
    private $pdo = null;

    /**
     * If no database file has been provided, memory will be used
     *
     * @param string $databaseFile [optional]
     */
    public function __construct($databaseFile = '')
    {
        $dsn = 'sqlite::memory:';

        if ($databaseFile) {
            $dsn = 'sqlite:' . $databaseFile;
        }

        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @param array $parameters
     * @return array
     */
    public function exec($sql, array $parameters = [])
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return string
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
