<?php
namespace Model\Action;
use Model\HttpRedirectResponse;
use Model\PartialHtmlResponse;
use Model\Request;
use Model\Template;
use Model\Url;
use Service\ServiceContainer;


class ViewTrashAction implements ActionInterface
{
    /** @var ServiceContainer */
    private $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param Request $request
     * @return PartialHtmlResponse
     */
    public function run(Request $request)
    {
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $userSession = $this->serviceContainer->getUserSession();
        $templateBuilder = $this->serviceContainer->getTemplateBuilder();
        $csrfHandler = $this->serviceContainer->getCsrfHandler();

        $user_id = $userSession->getUserId();

        $trashedList = $templateBuilder->createTemplate();
        $trashedList
            ->loadFile('trashedList.html')
            ->addPlaceHolder(
                'trashed_list',
                $this->renderTrashedEntriesForUser($user_id),
                Template::ESCAPE_MODE_NONE
            )
            ->addPlaceHolder(
                'list_urls_link',
                $urlBuilder->createActionUrl('listUrls')
            )
            ->addPlaceHolder(
                'empty_trash_link',
                $urlBuilder->createActionUrl('emptyTrash', 0, $csrfHandler->getCurrentToken())
            );

        return new PartialHtmlResponse($trashedList->render());
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function renderTrashedEntriesForUser($user_id)
    {
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $urlListEntryBuilder = $this->serviceContainer->getUrlListEntryBuilder();
        $csrfHandler = $this->serviceContainer->getCsrfHandler();

        $rendered = '';
        $urls = $this->getTrashedUrlsByUserId($user_id);

        foreach ($urls as $url) {
            $untrash_link = $urlBuilder->createActionUrl(
                'unTrashUrl',
                $url->getId(),
                $csrfHandler->getCurrentToken()
            );

            $urlListEntry = $urlListEntryBuilder->buildUrlListEntry($url, $untrash_link);
            $rendered .= $urlListEntry->render();
        }

        if (empty($urls)) {
            $rendered = '<p>No trashed items.</p>';
        }

        return $rendered;
    }

    /**
     * @param $user_id
     * @return Url[]
     */
    private function getTrashedUrlsByUserId($user_id)
    {
        $urls = [];

        $urlQuery = $this->serviceContainer->getUrlQuery();
        $urlMapper = $this->serviceContainer->getUrlDataObjectMapper();

        $urlDataObjects = $urlQuery->getTrashedUrlsByUserId($user_id);

        foreach ($urlDataObjects as $dataObject) {
            $urls[] = $urlMapper->mapFromDataObject($dataObject);
        }

        return $urls;
    }
}
