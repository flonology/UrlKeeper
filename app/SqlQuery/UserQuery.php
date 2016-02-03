<?php
namespace SqlQuery;
use Model\DataObject\UserDataObject;
use Service\PdoSqlite;


class UserQuery
{
    /** @var PdoSqlite */
    private $db;

    /**
     * @param PdoSqlite $db
     */
    public function __construct(PdoSqlite $db)
    {
        $this->db = $db;
    }

    /**
     * @param int $id
     * @return array | null
     */
    public function getUser($id)
    {
        $query = '
            SELECT id, name, password_hash
            FROM users WHERE id = :id
        ';

        $results = $this->db->exec($query, [':id' => $id]);
        if (empty($results)) {
            return null;
        }

        $result = $results[0];
        return $this->createUserDataObject($result);
    }

    /**
     * @param $name
     * @return array | null
     */
    public function getUserByName($name)
    {
        $query = '
            SELECT id, name, password_hash
            FROM users WHERE name = :name
        ';

        $results = $this->db->exec($query, [':name' => $name]);
        if (empty($results)) {
            return null;
        }

        $result = $results[0];
        return $this->createUserDataObject($result);
    }

    /**
     * @param array $result
     * @return UserDataObject
     */
    private function createUserDataObject(array $result)
    {
        $dataObject = new UserDataObject();
        $dataObject->name = $result['name'];
        $dataObject->id = $result['id'];
        $dataObject->passwordHash = $result['password_hash'];

        return $dataObject;
    }
}
