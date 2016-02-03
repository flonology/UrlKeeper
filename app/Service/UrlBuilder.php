<?php
namespace Service;


class UrlBuilder
{
    /** @var string */
    private $baseUrl = '';

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $action
     * @param int $id [optional]
     * @return string
     */
    public function createActionUrl($action, $id = 0)
    {
        $params = [];
        $params['action'] = $action;

        if ($id) {
            $params['id'] = $id;
        }

        return $this->baseUrl . '?' . http_build_query($params);
    }
}
