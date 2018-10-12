<?php
namespace TestHelper;
use Model\KeyValueStore;
use Model\Session;
use Model\SessionWrapperMock;
use TestHelper\UrlKeeperTestCase;

class SessionBuilder
{
    public static function createMockSession(UrlKeeperTestCase $testCase)
    {
        $keyValueStore = new KeyValueStore();

        $sessionWrapper = new SessionWrapperMock();
        $sessionWrapper->sessionName('Test Session');

        /** @var SessionWrapper $sessionWrapper */
        $session = new Session($sessionWrapper, $keyValueStore);
        $session->start();

        return $session;
    }
}
