<?php
namespace UrlKeeperTests;
use Service\ServiceContainer;
use Service\ConfigParams;
use Service\ActionBuilder;
use TestHelper\ConfigParamsBuilder;


class ActionBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ActionBuilder */
    private $actionBuilder;

    protected function setUp()
    {
        $serviceContainer = new ServiceContainer(ConfigParamsBuilder::createConfigParams());
        $this->actionBuilder = new ActionBuilder($serviceContainer);
    }


    public function testReturnsNotFoundActionIfNonExistingActionHasBeenRequested()
    {
        $result = $this->actionBuilder->createAction('SomeNonExistingAction');
        $this->assertInstanceOf('Model\Action\NotFoundAction', $result);
    }

    public function testReturnsValidActionIfRequested()
    {
        $result = $this->actionBuilder->createAction('listUrls');
        $this->assertInstanceOf('Model\Action\ListUrlsAction', $result);
    }
}
