<?php
namespace TestHelper;
use Service\PdoSqlite;


class Db
{
    /** @var PdoSqlite */
    private static $pdo = null;

    /**
     * @param string $db_file [optional]
     * @return PdoSqlite
     */
    public static function initDb($db_file = '')
    {
        self::$pdo = new PdoSqlite($db_file);
        self::createTables();

        return self::$pdo;
    }


    private static function createTables()
    {
        self::createUserTable();
        self::createUrlTable();
        self::insertUsers();
    }

    private static function createUserTable()
    {
        $sql = <<<'SQL_STATEMENT'
CREATE TABLE users
(
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  password_hash TEXT NOT NULL
);
SQL_STATEMENT;

        self::$pdo->exec($sql);

        $sql = <<<'SQL_STATEMENT'
CREATE UNIQUE INDEX users_name_uindex ON users (name);
SQL_STATEMENT;

        self::$pdo->exec($sql);
    }

    private static function createUrlTable()
    {
        $sql = <<<'SQL_STATEMENT'
CREATE TABLE urls
(
  id INTEGER PRIMARY KEY,
  url TEXT NOT NULL,
  title TEXT NOT NULL,
  description TEXT NOT NULL,
  created TEXT NOT NULL,
  updated TEXT NOT NULL,
  user_id INTEGER NOT NULL,
  trashed INTEGER NOT NULL DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users (id)
);
SQL_STATEMENT;

        self::$pdo->exec($sql);
    }

    /**
     * demo/demo
     * user/user
     */
    private static function insertUsers()
    {
        $sql = <<<'SQL_STATEMENT'
INSERT INTO users (name, password_hash)
VALUES ('demo', '$2y$10$z1JDj/OwmNO53NrJ5JBf7e560K.wMZ/qI75KOm66HeVCvrIAH5i8e');
SQL_STATEMENT;
        self::$pdo->exec($sql);

        $sql = <<<'SQL_STATEMENT'
INSERT INTO users (name, password_hash)
VALUES ('user', '$2y$10$7smqNme6IHUA5hYoTmoBLuoJN6cNrqDJI3S3ncTENWHExi86jI.T2');
SQL_STATEMENT;
        self::$pdo->exec($sql);

        $sql = <<<'SQL_STATEMENT'
INSERT INTO users (name, password_hash)
VALUES ('florian', '$2y$10$tfSWdnnAAGiCFkRa8uB80evMijOS1pfHINbLJOiKEH7C382tqOWuK');
SQL_STATEMENT;
        self::$pdo->exec($sql);
    }
}
