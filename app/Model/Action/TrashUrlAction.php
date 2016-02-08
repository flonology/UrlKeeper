<?php
namespace Model\Action;
use Model\CompleteHtmlResponse;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Service\ServiceContainer;


class TrashUrlAction implements ActionInterface
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
        $urlQuery = $this->serviceContainer->getUrlQuery();
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $userSession = $this->serviceContainer->getUserSession();

        $csrfHandler = $this->serviceContainer->getCsrfHandler();
        if ($csrfHandler->requestIsValid($request) == false) {
            return new CompleteHtmlResponse($csrfHandler->getErrorMessage());
        }

        $url_id = $request->getGetVal('id');
        $user_id = $userSession->getUserId();

        $urlQuery->trashUrlByIdAndUserId($url_id, $user_id);
        return new HttpRedirectResponse($urlBuilder->createActionUrl('listUrls'));
    }
}
