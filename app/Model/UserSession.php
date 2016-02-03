<?php
namespace Model;

/**
 * Class UserSession
 * @package Model
 */
class UserSession
{
    /** @var Session */
    private $session;

    /**
     * UserSession constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param int $user_id
     * @param string $loginName
     */
    public function loginAs($user_id, $loginName)
    {
        $this->session
            ->set('logged_in', true)
            ->set('login_name', $loginName)
            ->set('user_id', $user_id);
    }

    public function logout()
    {
        $this->session
            ->set('logged_in', false)
            ->set('login_name', '')
            ->set('user_id', 0);
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        if ($this->userIsLoggedIn() == false) {
            throw new \RuntimeException('User is not logged in.');
        }

        return $this->session->get('user_id');
    }

    /**
     * @return bool
     */
    public function userIsLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }

    /**
     * @return string
     */
    public function getLoginName()
    {
        return $this->session->get('login_name');
    }
}
