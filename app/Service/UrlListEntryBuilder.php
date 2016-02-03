<?php
namespace Service;


use Model\Template;
use Model\Url;

class UrlListEntryBuilder
{
    /** @var TemplateBuilder */
    private $templateBuilder;

    /**
     * @param TemplateBuilder $templateBuilder
     */
    public function __construct(TemplateBuilder $templateBuilder)
    {
        $this->templateBuilder = $templateBuilder;
    }

    /**
     * @param Url $url
     * @param string $edit_link
     * @return Template
     */
    public function buildUrlListEntry(Url $url, $edit_link)
    {
        $urlListEntry = $this->templateBuilder->createTemplate();
        $urlListEntry
            ->loadFile('urlListEntry.html')
            ->addPlaceHolder('id', $url->getId())
            ->addPlaceHolder('url', $url->getUrl())
            ->addPlaceHolder('title', $url->getTitle())
            ->addPlaceHolder('description', $url->getDescription())
            ->addPlaceHolder('updated', $url->getUpdated()->format('d.m.Y'))
            ->addPlaceHolder('edit_link', $edit_link);

        return $urlListEntry;
    }
}
