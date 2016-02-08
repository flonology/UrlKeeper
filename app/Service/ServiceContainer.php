<?php
namespace Service;
use DateTime;
use Mapper\UrlDataObjectMapper;
use Mapper\UrlFormMapper;
use Mapper\UserDataObjectMapper;
use Model\Session;
use Model\SessionBuilder;
use Model\UserSession;
use SqlQuery\UrlQuery;
use SqlQuery\UserQuery;


class ServiceContainer
{
    /** @var ConfigParams */
    private $configParams;

    /** @var TemplateBuilder */
    private $templateBuilder;

    /** @var UrlBuilder */
    private $urlBuilder;

    /** @var UrlFormMapper */
    private $urlFormMapper;

    /** @var PdoSqlite */
    private $db;

    /** @var UrlDataObjectMapper */
    private $urlDbMapper;

    /** @var UrlQuery */
    private $urlQuery;

    /** @var SessionBuilder */
    private $sessionBuilder;

    /** @var Session */
    private $session;

    /** @var UserSession */
    private $userSession;

    /** @var PasswordVerifier */
    private $passwordVerifier;

    /** @var UserQuery */
    private $userQuery;

    /** @var UserDataObjectMapper */
    private $userDbMapper;

    /** @var UserUrlLoader */
    private $userUrlLoader;

    /** @var UrlListEntryBuilder */
    private $urlListEntryBuilder;

    /** @var DateTime */
    private $currentDate;

    /** @var CsrfHandler */
    private $csrfHandler;

    /**
     * @param ConfigParams $configParams
     */
    public function __construct(ConfigParams $configParams)
    {
        $this->configParams = $configParams;
    }

    /**
     * @return PdoSqlite
     */
    public function getDb()
    {
        if ($this->db === null) {
            $databaseFile = $this->configParams->getAppDatabaseFile();
            $this->db = new PdoSqlite($databaseFile);
        }

        return $this->db;
    }

    /**
     * @return TemplateBuilder
     */
    public function getTemplateBuilder()
    {
        if ($this->templateBuilder === null) {
            $templatePath = $this->configParams->getAppTemplatePath();
            $this->templateBuilder = new TemplateBuilder($templatePath);
        }

        return $this->templateBuilder;
    }

    /**
     * @return UrlBuilder
     */
    public function getUrlBuilder()
    {
        if ($this->urlBuilder === null) {
            $baseUrl = $this->configParams->getAppIndex();
            $this->urlBuilder = new UrlBuilder($baseUrl);
        }

        return $this->urlBuilder;
    }

    /**
     * @return UrlFormMapper
     */
    public function getUrlFormMapper()
    {
        if ($this->urlFormMapper === null) {
            $this->urlFormMapper = new UrlFormMapper();
        }

        return $this->urlFormMapper;
    }

    /**
     * @return UrlDataObjectMapper
     */
    public function getUrlDataObjectMapper()
    {
        if ($this->urlDbMapper === null) {
            $this->urlDbMapper = new UrlDataObjectMapper();
        }

        return $this->urlDbMapper;
    }

    /**
     * @return UrlQuery
     */
    public function getUrlQuery()
    {
        if ($this->urlQuery === null) {
            $this->urlQuery = new UrlQuery($this->getDb());
        }

        return $this->urlQuery;
    }

    /**
     * @return SessionBuilder
     */
    public function getSessionBuilder()
    {
        if ($this->sessionBuilder === null) {
            $this->sessionBuilder = new SessionBuilder();
        }

        return $this->sessionBuilder;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if ($this->session === null) {
            $this->session = $this->getSessionBuilder()
                ->buildSession($this->configParams->getAppTitle())
                ->start();
        }

        return $this->session;
    }

    /**
     * @return ConfigParams
     */
    public function getConfigParams()
    {
        return $this->configParams;
    }

    /**
     * @return PasswordVerifier
     */
    public function getPasswordVerifier()
    {
        if ($this->passwordVerifier === null) {
            $this->passwordVerifier = new PasswordVerifier();
        }

        return $this->passwordVerifier;
    }

    /**
     * @return UserSession
     */
    public function getUserSession()
    {
        if ($this->userSession === null) {
            $session = $this->getSession();
            $this->userSession = new UserSession($session);
        }

        return $this->userSession;
    }

    /**
     * @return UserQuery
     */
    public function getUserQuery()
    {
        if ($this->userQuery === null) {
            $this->userQuery = new UserQuery($this->getDb());
        }

        return $this->userQuery;
    }

    /**
     * @return UserDataObjectMapper
     */
    public function getUserDbMapper()
    {
        if ($this->userDbMapper === null) {
            $this->userDbMapper = new UserDataObjectMapper();
        }

        return $this->userDbMapper;
    }

    /**
     * @return UserUrlLoader
     */
    public function getUserUrlLoader()
    {
        if ($this->userUrlLoader === null) {
            $this->userUrlLoader = new UserUrlLoader(
                $this->getUserSession(),
                $this->getUrlQuery(),
                $this->getUrlDataObjectMapper()
            );
        }

        return $this->userUrlLoader;
    }

    /**
     * @return UrlListEntryBuilder
     */
    public function getUrlListEntryBuilder()
    {
        if ($this->urlListEntryBuilder == null) {
            $this->urlListEntryBuilder = new UrlListEntryBuilder(
                $this->getTemplateBuilder()
            );
        }

        return $this->urlListEntryBuilder;
    }

    /**
     * @return DateTime
     */
    public function getCurrentDate()
    {
        if ($this->currentDate == null) {
            $this->currentDate = new DateTime();
        }

        return $this->currentDate;
    }

    /**
     * @return CsrfHandler
     */
    public function getCsrfHandler()
    {
        if ($this->csrfHandler == null) {
            $this->csrfHandler = new CsrfHandler($this->getSession());
        }

        return $this->csrfHandler;
    }
}
