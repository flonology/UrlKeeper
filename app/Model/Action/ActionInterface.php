<?php
namespace Model\Action;
use Model\Request;
use Model\ResponseInterface;
use Service\ServiceContainer;


interface ActionInterface
{
    /**
     * @param Request $request
     * @return ResponseInterface
     */
    public function run(Request $request);

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer);
}
