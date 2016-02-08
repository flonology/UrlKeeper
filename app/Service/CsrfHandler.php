<?php
namespace Service;
use Model\Request;
use Model\Session;


class CsrfHandler
{
    /** @var Session */
    private $session;

    /** @var string */
    private $tokenFieldName = '_csrf';

    /**
     * CsrfHandler constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function requestIsValid(Request $request)
    {
        $token = $this->getTokenFromRequest($request);

        if ($token === null) {
            return false;
        }

        if ($token === '') {
            return false;
        }

        if ($token === $this->getCurrentToken()) {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getCurrentToken()
    {
        return $this->session->get($this->tokenFieldName);
    }

    /**
     * @return $this
     */
    public function createNewToken()
    {
        $byteLength = 32;

        $token = bin2hex(openssl_random_pseudo_bytes($byteLength));
        $this->session->set($this->tokenFieldName, $token);

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return 'Invalid token submitted.';
    }

    /**
     * @param Request $request
     * @return string|null
     */
    private function getTokenFromRequest(Request $request)
    {
        $token = $request->getPostVal($this->tokenFieldName);
        if ($token !== null) {
            return $token;
        }

        return $request->getGetVal($this->tokenFieldName);
    }

    /**
     * @return string
     */
    public function getTokenFieldName()
    {
        return $this->tokenFieldName;
    }
}
