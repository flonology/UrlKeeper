<?php
namespace Model\Action;
use Model\DataObject\UrlDataObject;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Service\ServiceContainer;


class UpdateUrlAction implements ActionInterface
{
    /** @var ServiceContainer */
    private $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
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
        $dbQuery = $this->serviceContainer->getUrlQuery();

        $urlDataObject = $formMapper->mapToDataObject($request, $userSession->getUserId());
        $formMapper->initValues($urlDataObject, $now);

        $dbQuery->updateUrl($urlDataObject);
        return new HttpRedirectResponse($urlBuilder->createActionUrl('listUrls'));
    }
}
