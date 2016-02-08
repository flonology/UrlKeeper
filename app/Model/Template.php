<?php
namespace Model;

use Service\CsrfHandler;

class Template
{
    const ESCAPE_MODE_HTML = 'html';
    const ESCAPE_MODE_NONE = 'none';

    private $content = '';
    private $placeHolders = [];
    private $fileBasePath = '';

    /**
     * @param string $fileBasePath
     */
    public function __construct($fileBasePath)
    {
        $this->fileBasePath = $fileBasePath;
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->replacePlaceHolders($this->placeHolders);
        return $this->content;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $escapeMode (ESCAPE_MODE_*)
     * @return $this
     */
    public function addPlaceHolder($name, $value, $escapeMode = self::ESCAPE_MODE_HTML)
    {
        if ($escapeMode == self::ESCAPE_MODE_HTML) {
            $value = $this->htmlEscape($value);
        }

        $this->placeHolders[$name] = $value;
        return $this;
    }

    /**
     * @param array $placeHolderReplacements
     * @return $this
     */
    private function replacePlaceHolders(array $placeHolderReplacements)
    {
        foreach ($placeHolderReplacements as $placeHolder => $replacement) {
            $placeHolder = '::' . $placeHolder . '::';
            $this->content = str_replace($placeHolder, $replacement, $this->content);
        }

        return $this;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function loadFile($fileName)
    {
        $this->content = file_get_contents($this->fileBasePath . '/' . $fileName);
        return $this;
    }

    /**
     * @param CsrfHandler $csrfHandler
     * @return $this
     */
    public function addCsrfToken(CsrfHandler $csrfHandler)
    {
        $this->addPlaceHolder('csrf_token', $csrfHandler->getCurrentToken());
        $this->addPlaceHolder('csrf_field_name', $csrfHandler->getTokenFieldName());

        return $this;
    }

    /**
     * @param string $unescaped
     * @return string
     */
    private function htmlEscape($unescaped)
    {
        return htmlspecialchars($unescaped);
    }
}
