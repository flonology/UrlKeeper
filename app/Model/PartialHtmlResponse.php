<?php
namespace Model;


class PartialHtmlResponse implements ResponseInterface
{
    /** @var string */
    private $content;


    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
