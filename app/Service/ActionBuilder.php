<?php
namespace Service;

use Model\Action\ActionInterface;
use Model\Action\NotFoundAction;


class ActionBuilder
{
    /** @var ServiceContainer */
    private $serviceContainer;
    private $defaultActionName = 'listUrls';

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param string $actionName
     * @return ActionInterface
     */
    public function createAction($actionName)
    {
        $actionName = $actionName ? $actionName : $this->defaultActionName;

        // Make first letter upper case
        $actionName = strtoupper(substr($actionName, 0, 1)) . substr($actionName, 1);

        $className = '\\Model\\Action\\' . $actionName . 'Action';
        if (class_exists($className) == false) {
            return new NotFoundAction($this->serviceContainer);
        }

        return new $className($this->serviceContainer);
    }
}
