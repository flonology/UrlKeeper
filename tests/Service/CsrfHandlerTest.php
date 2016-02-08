<?php
namespace UrlKeeperTests;
use Model\Request;
use Model\Session;
use Service\CsrfHandler;
use TestHelper\SessionBuilder;


class CsrfHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CsrfHandler */
    private $csrfHandler;

    /** @var Session */
    private $session;

    protected function setUp()
    {
        $this->session = SessionBuilder::createMockSession($this);
        $this->csrfHandler = new CsrfHandler($this->session);
    }


    public function testRequestIsInvalidIfNoTokenProvided()
    {
        $get = [];
        $post = [];

        $request = new Request($get, $post);
        $this->assertFalse($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsInvalidIfEmptyTokenProvided()
    {
        $get = [];
        $post = ['_csrf' => ''];

        $request = new Request($get, $post);
        $this->assertFalse($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsInvalidIfSessionHasNoToken()
    {
        $get = [];
        $post = ['_csrf' => 'some token'];

        $request = new Request($get, $post);
        $this->assertFalse($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsInvalidIfSessionHasEmptyToken()
    {
        $get = [];
        $post = ['_csrf' => 'some token'];
        $this->session->set('_csrf', '');

        $request = new Request($get, $post);
        $this->assertFalse($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsInvalidIfSessionHasInvalidToken()
    {
        $get = [];
        $post = ['_csrf' => 'some token'];
        $this->session->set('_csrf', 'invalid token');

        $request = new Request($get, $post);
        $this->assertFalse($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsValidIfSessionHasCorrectToken()
    {
        $get = [];
        $post = ['_csrf' => 'some token'];
        $this->session->set('_csrf', 'some token');

        $request = new Request($get, $post);
        $this->assertTrue($this->csrfHandler->requestIsValid($request));
    }

    public function testRequestIsAlsoValidViaGetParameter()
    {
        $get = ['_csrf' => 'some token'];
        $post = [];
        $this->session->set('_csrf', 'some token');

        $request = new Request($get, $post);
        $this->assertTrue($this->csrfHandler->requestIsValid($request));
    }

    public function testCreateTokenCreatesNewTokenInSession()
    {
        $newToken = $this->csrfHandler->createNewToken()->getCurrentToken();
        $this->assertNotEmpty($newToken);

        $this->assertEquals($newToken, $this->csrfHandler->getCurrentToken());
        $this->assertEquals($newToken, $this->session->get('_csrf'));

        $anotherToken = $this->csrfHandler->createNewToken()->getCurrentToken();
        $this->assertNotEmpty($anotherToken);
        $this->assertNotEquals($newToken, $anotherToken);
    }
}
