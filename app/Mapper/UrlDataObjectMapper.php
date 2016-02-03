<?php
namespace Mapper;
use DateTime;
use Model\DataObject\UrlDataObject;
use Model\Url;


class UrlDataObjectMapper
{
    /**
     * @param UrlDataObject $urlDataObject
     * @return Url
     */
    public function mapFromDataObject(UrlDataObject $urlDataObject)
    {
        $url = new Url(
            (int)$urlDataObject->id,
            (int)$urlDataObject->userId,
            (string)$urlDataObject->url,
            (string)$urlDataObject->title,
            (string)$urlDataObject->description,
            new DateTime($urlDataObject->created),
            new DateTime($urlDataObject->updated)
        );

        return $url;
    }

    /**
     * @param Url $url
     * @return UrlDataObject
     */
    public function mapToDataObject(Url $url)
    {
        $urlDataObject = new UrlDataObject();
        $urlDataObject->id = $url->getId();
        $urlDataObject->userId = $url->getUserId();
        $urlDataObject->url = $url->getUrl();
        $urlDataObject->title = $url->getTitle();
        $urlDataObject->description = $url->getDescription();
        $urlDataObject->created = $url->getCreated()->format('c');
        $urlDataObject->updated = $url->getUpdated()->format('c');

        return $urlDataObject;
    }
}
