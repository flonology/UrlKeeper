<?php
namespace Model\Action;
use Model\DataObject\UrlDataObject;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Model\Url;
use Service\PdoSqlite;
use Service\ServiceContainer;


class CreateUrlAction implements ActionInterface
{
    /** @var ServiceContainer */
    private $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param Request $request
     * @return PartialHtmlResponse
     */
    public function run(Request $request)
    {
        $now = $this->serviceContainer->getCurrentDate();
        $userSession = $this->serviceContainer->getUserSession();
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $formMapper = $this->serviceContainer->getUrlFormMapper();

        $urlDataObject = $formMapper->mapToDataObject($request, $userSession->getUserId());
        $formMapper->initValues($urlDataObject, $now);

        $this->addUrlToDb($urlDataObject);
        return new HttpRedirectResponse($urlBuilder->createActionUrl('listUrls'));
    }

    /**
     * @param UrlDataObject $url
     * @return UrlDataObject (with newly created id)
     */
    private function addUrlToDb(UrlDataObject $url)
    {
        return $this->serviceContainer->getUrlQuery()->createUrl($url);
    }
}
