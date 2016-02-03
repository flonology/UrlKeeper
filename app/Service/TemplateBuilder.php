<?php
namespace Service;
use Model\Template;


class TemplateBuilder
{
    /** @var string */
    private $templatePath = '';

    /**
     * @param string $templatePath
     */
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @return Template
     */
    public function createTemplate()
    {
        return new Template($this->templatePath);
    }

    public function createBaseTemplate(ConfigParams $params)
    {
        $template = $this->createTemplate();
        $template
            ->loadFile('base.html')
            ->addPlaceHolder('title', $params->getAppTitle())
            ->addPlaceHolder('app_version', $params->getAppVersion());

        return $template;
    }
}