<?php
namespace Service;
use Model\User;


class PasswordVerifier
{
    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function verifyUserPassword(User $user, $password)
    {
        return $this->verifyPassword($password, $user->getPasswordHash());
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    private function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
