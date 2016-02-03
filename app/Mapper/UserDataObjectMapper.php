<?php
namespace Mapper;
use Model\DataObject\UserDataObject;
use Model\User;


class UserDataObjectMapper
{
    /**
     * @param UserDataObject $userDataObject
     * @return User
     */
    public function mapFromDataObject(UserDataObject $userDataObject)
    {
        $user = new User(
            (int)$userDataObject->id,
            (string)$userDataObject->name,
            (string)$userDataObject->passwordHash
        );

        return $user;
    }

    /**
     * @param User $user
     * @return UserDataObject
     */
    public function mapToDataObject(User $user)
    {
        $userDataObject = new UserDataObject();
        $userDataObject->id = $user->getId();
        $userDataObject->name = $user->getName();
        $userDataObject->passwordHash = $user->getPasswordHash();

        return $userDataObject;
    }
}
