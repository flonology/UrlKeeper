<?php
namespace Model;


class Request
{
    private $get = [];
    private $post = [];

    /**
     * @param array $get
     * @param array $post
     */
    public function __construct(array $get, array $post)
    {
        $this->get = $get;
        $this->post = $post;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @return Request
     */
    public static function createFromGlobals()
    {
        return new self($_GET, $_POST);
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param string $name
     * @return mixed | null
     */
    public function getGetVal($name)
    {
        if (empty($this->get[$name])) {
            return null;
        }

        return $this->get[$name];
    }

    /**
     * @param string $name
     * @return mixed | null
     */
    public function getPostVal($name)
    {
        if (empty($this->post[$name])) {
            return null;
        }

        return $this->post[$name];
    }
}