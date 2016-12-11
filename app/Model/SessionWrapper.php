<?php
namespace Model;


class SessionWrapper implements SessionWrapperInterface
{
    public function __construct($lifeTime = 86400)
    {
        session_set_cookie_params($lifeTime);
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
