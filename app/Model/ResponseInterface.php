<?php
namespace Model;


interface ResponseInterface
{
    /**
     * @param string $content
     */
    public function __construct($content);

    /**
     * @return string
     */
    public function getContent();
}
