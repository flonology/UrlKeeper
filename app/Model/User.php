<?php
namespace Model;


class User
{
    /** @var int */
    private $id = 0;

    /** @var string */
    private $name = '';

    /** @var string  */
    private $passwordHash = '';

    /**
     * @param int $id
     * @param string $name
     * @param string $passwordHash
     */
    public function __construct($id, $name, $passwordHash)
    {
        if (is_int($id) === false || $id < 1) {
            throw new \RuntimeException('User id must be an integer bigger than 0.');
        }

        $this->id = $id;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
}
