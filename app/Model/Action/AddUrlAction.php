<?php
namespace Model\Action;

use Model\PartialHtmlResponse;
use Model\Request;
use Service\ServiceContainer;


class AddUrlAction implements ActionInterface
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
        $addUrl = $this->serviceContainer->getTemplateBuilder()->createTemplate();
        $url = $this->serviceContainer->getUrlBuilder()->createActionUrl('createUrl');

        $addUrl
            ->loadFile('addUrl.html')
            ->addPlaceHolder('form_action', $url)
            ->addPlaceHolder('list_urls_link', $this->serviceContainer->getUrlBuilder()->createActionUrl('listUrls'));

        return new PartialHtmlResponse($addUrl->render());
    }
}
