<?php
namespace Model\Action;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Service\ServiceContainer;


class LogoutAction implements ActionInterface
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
        $userSession = $this->serviceContainer->getUserSession();
        $userSession->logout();

        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        return new HttpRedirectResponse($urlBuilder->createActionUrl('login'));
    }
}
