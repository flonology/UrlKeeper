<?php
namespace Model;
use Service\ActionBuilder;
use Service\ConfigParams;
use Service\ServiceContainer;


/**
 * Class App
 * @package Model
 */
class App
{
    /** @var ServiceContainer */
    private $serviceContainer;

    /** @var ActionBuilder */
    private $actionBuilder;

    /**
     * @param ConfigParams $configParams
     */
    public function __construct(ConfigParams $configParams)
    {
        $this->serviceContainer = new ServiceContainer($configParams);
        $this->actionBuilder = new ActionBuilder($this->serviceContainer);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function run(Request $request)
    {
        $this->redirectToLoginIfNotLoggedIn($request);
        $actionName = $request->getGetVal('action');
        $action = $this->actionBuilder->createAction($actionName);

        $response = $action->run($request);
        $this->getServiceContainer()->getSession()->save();

        return $this->handleResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function handleResponse(ResponseInterface $response)
    {
        if ($response instanceof HttpRedirectResponse) {
            header(sprintf('Location: %s', $response->getContent()));
            return '';
        }

        if ($response instanceof PartialHtmlResponse) {
            $response = $this->buildCompleteHtmlResponse($response);
        }

        if ($response instanceof NotFoundResponse) {
            http_response_code(404);
        }

        return $response->getContent();
    }

    /**
     * @return Template
     */
    private function getBaseTemplate()
    {
        $configParams = $this->serviceContainer->getConfigParams();
        $templateBuilder = $this->serviceContainer->getTemplateBuilder();

        $template = $templateBuilder->createTemplate();
        $template
            ->loadFile('base.html')
            ->addPlaceHolder('title', $configParams->getAppTitle())
            ->addPlaceHolder('app_version', $configParams->getAppVersion())
            ->addPlaceHolder('logout_form', $this->renderLogoutForm(), Template::ESCAPE_MODE_NONE)
            ->addPlaceHolder('custom_css', $this->renderCssLink('custom.css'));

        return $template;
    }

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    private function renderLogoutForm()
    {
        $templateBuilder = $this->serviceContainer->getTemplateBuilder();
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $userSession = $this->serviceContainer->getUserSession();

        if ($userSession->userIsLoggedIn() === false) {
            return '';
        }

        $actionUrl = $urlBuilder->createActionUrl('logout');

        $template = $templateBuilder->createTemplate();
        $template
            ->loadFile('logoutForm.html')
            ->addPlaceHolder('login_name', $userSession->getLoginName())
            ->addPlaceHolder('form_action', $actionUrl);

        return $template->render();
    }

    /**
     * @param PartialHtmlResponse $response
     * @return CompleteHtmlResponse
     */
    private function buildCompleteHtmlResponse($response)
    {
        $baseTemplate = $this->getBaseTemplate();
        $baseTemplate->addPlaceHolder('content', $response->getContent(), Template::ESCAPE_MODE_NONE);

        $response = new CompleteHtmlResponse($baseTemplate->render());
        return $response;
    }

    /**
     * @param string $cssFileName
     * @return string
     */
    private function renderCssLink($cssFileName)
    {
        return $this->serviceContainer->getConfigParams()->getAppUrlBase() . 'css/' . $cssFileName;
    }

    /**
     * Does not feel right yet...
     * @param Request $request
     */
    private function redirectToLoginIfNotLoggedIn(Request $request)
    {
        $userSession = $this->serviceContainer->getUserSession();
        if ($userSession->userIsLoggedIn()) {
            return;
        }

        $actionName = $request->getGetVal('action');
        if ($actionName == 'login' || $actionName == 'performLogin') {
            return;
        }

        $loginUrl = $this->serviceContainer->getUrlBuilder()->createActionUrl('login');
        $redirectResponse = new HttpRedirectResponse($loginUrl);

        // Maybe just make a redirect method which always exits
        $this->handleResponse($redirectResponse);
        exit();
    }
}
