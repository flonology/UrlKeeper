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
     * @param string $csrf_token [optional]
     * @return string
     */
    public function createActionUrl($action, $id = 0, $csrf_token = '')
    {
        $params = [];
        $params['action'] = $action;

        if ($id) {
            $params['id'] = $id;
        }

        if ($csrf_token) {
            $params['_csrf'] = $csrf_token;
        }

        return $this->baseUrl . '?' . http_build_query($params);
    }
}
