<?php
namespace TestHelper;
use Model\KeyValueStore;
use Model\Session;
use Model\SessionWrapper;


class SessionBuilder
{
    public static function createMockSession(\PHPUnit_Framework_TestCase $testCase)
    {
        $keyValueStore = new KeyValueStore();

        $sessionWrapper = $testCase->getMockBuilder(SessionWrapper::class)->getMock();
        $sessionWrapper->method('getValues')->willReturn([]);
        $sessionWrapper->method('sessionStart')->willReturn(true);
        $sessionWrapper->method('sessionName')->willReturn('Test Session');

        /** @var SessionWrapper $sessionWrapper */
        $session = new Session($sessionWrapper, $keyValueStore);
        $session->start();

        return $session;
    }
}
