<?php
namespace Model;


class SessionWrapper implements SessionWrapperInterface
{
    const YEAR = 31536000;
    const MONTH = 2592000;

    public function __construct($lifeTime = self::YEAR, $gcMaxlifetime = self::MONTH)
    {
        session_set_cookie_params($lifeTime);
        session_save_path(APP_PATH_SESSIONS);
        ini_set('session.gc_maxlifetime', $gcMaxlifetime);
    }

    /**
     * @return bool
     */
    public function sessionStart()
    {
        return session_start();
    }

    /**
     * @param string $name
     * @return string
     */
    public function sessionName($name)
    {
        return session_name($name);
    }

    /**
     * @param array $values
     * @return $this
     */
    public function save(array $values)
    {
        $_SESSION = $values;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $_SESSION;
    }

    /**
     * @return boolean
     */
    public function close()
    {
        $_SESSION = [];
        return true;
    }
}
