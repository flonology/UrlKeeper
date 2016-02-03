<?php
namespace Service;

use Model\Action\ActionInterface;


class ActionBuilder
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
     * @param string $actionName
     * @return ActionInterface
     */
    public function createAction($actionName)
    {
        // Make first letter upper case
        $actionName = strtoupper(substr($actionName, 0, 1)) . substr($actionName, 1);

        $className = '\\Model\\Action\\' . $actionName . 'Action';
        if (class_exists($className) == false) {
            throw new \RuntimeException(sprintf('Action %s not found.', $actionName));
        }

        return new $className($this->serviceContainer);
    }
}
