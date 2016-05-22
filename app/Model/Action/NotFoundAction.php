<?php
namespace Model\Action;
use Model\NotFoundResponse;
use Model\Request;
use Service\ServiceContainer;


class NotFoundAction implements ActionInterface
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
        return new NotFoundResponse('The action you requested does not exist.');
    }
}
