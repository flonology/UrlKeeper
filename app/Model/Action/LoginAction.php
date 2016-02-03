<?php
namespace Model\Action;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Service\ServiceContainer;


class LoginAction implements ActionInterface
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
        $urlBuilder = $this->serviceContainer->getUrlBuilder();

        if ($userSession->userIsLoggedIn()) {
            return new HttpRedirectResponse($urlBuilder->createActionUrl('listUrls'));
        }

        $templateBuilder = $this->serviceContainer->getTemplateBuilder();
        $urlBuilder = $this->serviceContainer->getUrlBuilder();

        $actionUrl = $urlBuilder->createActionUrl('performLogin');
        $loginForm = $templateBuilder->createTemplate();
        $loginForm
            ->loadFile('loginForm.html')
            ->addPlaceHolder('form_action', $actionUrl);

        return new PartialHtmlResponse($loginForm->render());
    }
}
