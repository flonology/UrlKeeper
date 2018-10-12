<?php
namespace UrlKeeperTests;
use Model\DataObject\UserDataObject;
use Service\PdoSqlite;
use SqlQuery\UserQuery;
use TestHelper\Db;
use TestHelper\UrlKeeperTestCase;

class UserQueryTest extends UrlKeeperTestCase
{
    /** @var PdoSqlite */
    private $pdo;

    /** @var UserQuery */
    private $userQuery;

    protected function setUp()
    {
        $this->pdo = Db::initDb();
        $this->userQuery = new UserQuery($this->pdo);
    }


    public function testGetUserById()
    {
        $result = $this->userQuery->getUser(1);
        $userDataObject = $this->getDemoUserDataObject();
        $this->assertEquals($userDataObject, $result);

        $result = $this->userQuery->getUser(2);
        $userDataObject = $this->getUserUserDataObject();
        $this->assertEquals($userDataObject, $result);
    }


    public function testGetUseByName()
    {
        $result = $this->userQuery->getUserByName('demo');
        $userDataObject = $this->getDemoUserDataObject();
        $this->assertEquals($userDataObject, $result);

        $result = $this->userQuery->getUserByName('user');
        $userDataObject = $this->getUserUserDataObject();
        $this->assertEquals($userDataObject, $result);
    }


    /**
     * @return UserDataObject
     */
    private function getDemoUserDataObject()
    {
        $userDataObject = new UserDataObject();
        $userDataObject->id = 1;
        $userDataObject->name = 'demo';
        $userDataObject->passwordHash = '$2y$10$z1JDj/OwmNO53NrJ5JBf7e560K.wMZ/qI75KOm66HeVCvrIAH5i8e';
        return $userDataObject;
    }

    /**
     * @return UserDataObject
     */
    private function getUserUserDataObject()
    {
        $userDataObject = new UserDataObject();
        $userDataObject->id = 2;
        $userDataObject->name = 'user';
        $userDataObject->passwordHash = '$2y$10$7smqNme6IHUA5hYoTmoBLuoJN6cNrqDJI3S3ncTENWHExi86jI.T2';
        return $userDataObject;
    }
}
