<?php
namespace Service;


class ConfigParams
{
    private $appPath = '';
    private $appUrlBase = '';
    private $appIndex = '';
    private $appTemplatePath = '';
    private $appTitle = '';
    private $appVersion = '';
    private $appDatabaseFile = '';

    public function __construct(
        $appPath,
        $appUrlBase,
        $appIndex,
        $appTemplatePath,
        $appTitle,
        $appVersion,
        $appDatabaseFile
    ) {
        $this->appPath = $appPath;
        $this->appUrlBase = $appUrlBase;
        $this->appIndex = $appIndex;
        $this->appTemplatePath = $appTemplatePath;
        $this->appTitle = $appTitle;
        $this->appVersion = $appVersion;
        $this->appDatabaseFile = $appDatabaseFile;
    }

    /**
     * @return string
     */
    public function getAppPath()
    {
        return $this->appPath;
    }

    /**
     * @return string
     */
    public function getAppUrlBase()
    {
        return $this->appUrlBase;
    }

    /**
     * @return string
     */
    public function getAppTemplatePath()
    {
        return $this->appTemplatePath;
    }

    /**
     * @return string
     */
    public function getAppTitle()
    {
        return $this->appTitle;
    }

    /**
     * @return string
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * @return string
     */
    public function getAppDatabaseFile()
    {
        return $this->appDatabaseFile;
    }

    /**
     * @return string
     */
    public function getAppIndex()
    {
        return $this->appIndex;
    }
}
