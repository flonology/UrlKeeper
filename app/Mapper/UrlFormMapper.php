<?php
namespace Mapper;
use DateTime;
use Model\DataObject\UrlDataObject;
use Model\Request;


class UrlFormMapper
{
    /**
     * @param Request $request
     * @param int $user_id
     * @return UrlDataObject
     */
    public function mapToDataObject(Request $request, $user_id)
    {
        $urlDataObject = new UrlDataObject();
        $urlDataObject->userId = $user_id;
        $urlDataObject->id = $request->getGetVal('id');
        $urlDataObject->url = $request->getPostVal('url');
        $urlDataObject->title = $request->getPostVal('title');
        $urlDataObject->description = $request->getPostVal('description');
        $urlDataObject->created = $request->getPostVal('created');
        $urlDataObject->updated = $request->getPostVal('update');

        return $urlDataObject;
    }

    /**
     * @param UrlDataObject $urlDataObject
     * @param DateTime $date
     */
    public function initValues(UrlDataObject $urlDataObject, DateTime $date)
    {
        $urlDataObject->url = $urlDataObject->url ?: '';
        $urlDataObject->title = $urlDataObject->title ?: '';
        $urlDataObject->description = $urlDataObject->description ?: '';
        $urlDataObject->created = $urlDataObject->created ?: $this->getTodayAsISO8601($date);
        $urlDataObject->updated = $urlDataObject->updated ?: $this->getTodayAsISO8601($date);
    }

    /**
     * @param DateTime $date
     * @return string
     */
    private function getTodayAsISO8601(DateTime $date)
    {
        return $date->format('c');
    }
}
