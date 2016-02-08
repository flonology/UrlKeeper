<?php
namespace Model\Action;
use Model\PartialHtmlResponse;
use Model\Request;
use Model\Url;
use Service\ServiceContainer;


class EditUrlAction implements ActionInterface
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
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $editUrl = $this->serviceContainer->getTemplateBuilder()->createTemplate();
        $userUrlLoader = $this->serviceContainer->getUserUrlLoader();
        $csrfHandler = $this->serviceContainer->getCsrfHandler()->createNewToken();

        $url = $userUrlLoader->loadUrl($request->getGetVal('id'));
        if ($url == null) {
            return new PartialHtmlResponse('Sorry, you are not allowed to edit this url.');
        }

        $update_action_url = $urlBuilder->createActionUrl(
            'updateUrl',
            $request->getGetVal('id')
        );

        $trash_link = $urlBuilder->createActionUrl(
            'trashUrl',
            $url->getId(),
            $csrfHandler->getCurrentToken()
        );

        $editUrl
            ->loadFile('editUrl.html')
            ->addPlaceHolder('form_action', $update_action_url)
            ->addPlaceHolder('url', $url->getUrl())
            ->addPlaceHolder('title', $url->getTitle())
            ->addPlaceHolder('description', $url->getDescription())
            ->addPlaceHolder('trash_link', $trash_link)
            ->addPlaceHolder('list_urls_link', $urlBuilder->createActionUrl('listUrls'))
            ->addCsrfToken($csrfHandler);

        return new PartialHtmlResponse($editUrl->render());
    }
}
