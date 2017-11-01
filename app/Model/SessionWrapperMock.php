<?php
namespace Model;


class SessionWrapperMock implements SessionWrapperInterface
{
    /** @var string */
    private $name = '';

    /** @var array */
    private $session = [];

    /**
     * @return bool
     */
    public function sessionStart()
    {
        return true;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function save(array $values)
    {
        $this->session = $values;
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    public function sessionName($name)
    {
        if ($name == '') {
            return $this->name;
        }

        $this->name = $name;
        return $name;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->session;
    }

    /**
     * @return bool
     */
    public function close()
    {
        $this->session = [];
        return true;
    }
}
