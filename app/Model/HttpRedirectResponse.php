<?php
namespace Model;


class HttpRedirectResponse implements ResponseInterface
{
    /** @var string */
    private $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->url;
    }
}
