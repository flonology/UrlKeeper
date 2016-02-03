<?php
namespace Service;


use Mapper\UrlDataObjectMapper;
use Model\Url;
use Model\UserSession;
use SqlQuery\UrlQuery;

class UserUrlLoader
{
    /** @var UserSession */
    private $userSession;

    /** @var UrlQuery */
    private $urlQuery;

    /** @var UrlDataObjectMapper */
    private $urlDataObjectMapper;

    public function __construct(
        UserSession $userSession,
        UrlQuery $urlQuery,
        UrlDataObjectMapper $urlDataObjectMapper
    ) {
        $this->userSession = $userSession;
        $this->urlQuery = $urlQuery;
        $this->urlDataObjectMapper = $urlDataObjectMapper;
    }


    /**
     * @param $url_id
     * @return Url | null
     */
    public function loadUrl($url_id)
    {
        $user_id = $this->userSession->getUserId();

        $dataObject = $this->urlQuery->getUrlByIdAndUserId($url_id, $user_id);
        if ($dataObject == null) {
            return null;
        }

        return $this->urlDataObjectMapper->mapFromDataObject($dataObject);
    }
}
