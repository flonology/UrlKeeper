<?php
namespace Model\Action;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Model\User;
use Service\ServiceContainer;


class PerformLoginAction implements ActionInterface
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
        $userName = $request->getPostVal('user');
        $password = $request->getPostVal('password');

        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $passwordVerifier = $this->serviceContainer->getPasswordVerifier();
        $userSession = $this->serviceContainer->getUserSession();

        $user = $this->getUserByName($userName);
        if ($user == null) {
            return new HttpRedirectResponse($urlBuilder->createActionUrl('login'));
        }

        if ($passwordVerifier->verifyUserPassword($user, $password)) {
            $userSession->loginAs($user->getId(), $user->getName());
        } else {
            return new HttpRedirectResponse($urlBuilder->createActionUrl('login'));
        }

        return new HttpRedirectResponse($urlBuilder->createActionUrl('listUrls'));
    }

    /**
     * @param $userName
     * @return User | null
     */
    private function getUserByName($userName)
    {
        $userQuery = $this->serviceContainer->getUserQuery();
        $userDbMapper = $this->serviceContainer->getUserDbMapper();

        $userDataObject = $userQuery->getUserByName($userName);
        if ($userDataObject == null) {
            return null;
        }

        return $userDbMapper->mapFromDataObject($userDataObject);
    }
}
