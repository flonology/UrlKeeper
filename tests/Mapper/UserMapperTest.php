<?php
namespace UrlKeeperTests;
use Mapper\UserDataObjectMapper;
use Model\DataObject\UserDataObject;
use TestHelper\UrlKeeperTestCase;


class UserMapperTest extends UrlKeeperTestCase
{
    /** @var UserDataObjectMapper */
    private $userDataObjectMapper;

    protected function setUp()
    {
        $this->userDataObjectMapper = new UserDataObjectMapper();
    }

    public function testDbMapperMapsFromAndToDataObject()
    {
        $dataObject = $this->createExampleUserDataObject(1);

        $user = $this->userDataObjectMapper->mapFromDataObject($dataObject);
        $mappedDataObject = $this->userDataObjectMapper->mapToDataObject($user);

        $this->assertInstanceOf('\Model\User', $user);
        $this->assertEquals($dataObject, $mappedDataObject);
    }

    /**
     * @param string $id
     * @return UserDataObject
     */
    private function createExampleUserDataObject($id)
    {
        $user = new UserDataObject();
        $user->id = $id;
        $user->name = 'User ' . uniqid();
        $user->passwordHash = 'PasswordHash ' . uniqid();

        return $user;
    }
}
