<?php
namespace Model;


interface SessionWrapperInterface
{
    /**
     * @return bool
     */
    public function sessionStart();

    /**
     * @param string $name
     * @return string
     */
    public function sessionName($name);

    /**
     * @param array $values
     * @return $this
     */
    public function save(array $values);

    /**
     * @return array
     */
    public function getValues();

    /**
     * @return boolean
     */
    public function close();
}
