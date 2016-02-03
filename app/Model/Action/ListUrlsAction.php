<?php
namespace Model\Action;
use Model\PartialHtmlResponse;
use Model\Request;
use Model\Template;
use Model\Url;
use Service\ServiceContainer;


class ListUrlsAction implements ActionInterface
{
    /** @var ServiceContainer */
    private $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer) {
        $this->serviceContainer = $serviceContainer;
    }


    /**
     * @param Request $request
     * @return PartialHtmlResponse
     */
    public function run(Request $request)
    {
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $templateBuilder = $this->serviceContainer->getTemplateBuilder();
        $userSession = $this->serviceContainer->getUserSession();

        $urlList = $templateBuilder->createTemplate();
        $urlList
            ->loadFile('urlList.html')
            ->addPlaceHolder(
                'url_list',
                $this->renderUrlListEntriesForUser($userSession->getUserId()),
                Template::ESCAPE_MODE_NONE
            )
            ->addPlaceHolder(
                'add_url_link',
                $urlBuilder->createActionUrl('addUrl')
            )
            ->addPlaceHolder(
                'view_trash_link',
                $urlBuilder->createActionUrl('viewTrash')
            );

        return new PartialHtmlResponse($urlList->render());
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function renderUrlListEntriesForUser($user_id)
    {
        $urlBuilder = $this->serviceContainer->getUrlBuilder();
        $urlListEntryBuilder = $this->serviceContainer->getUrlListEntryBuilder();

        $rendered = '';
        $urls = $this->getUrlsByUserId($user_id);

        if (empty($urls)) {
            $rendered = '<p>No URLs here.</p>';
        }

        foreach ($urls as $url) {
            $edit_link = $urlBuilder->createActionUrl('editUrl', $url->getId());

            $urlListEntry = $urlListEntryBuilder->buildUrlListEntry($url, $edit_link);
            $rendered .= $urlListEntry->render();
        }

        return $rendered;
    }

    /**
     * @param int $user_id
     * @return Url[]
     */
    private function getUrlsByUserId($user_id)
    {
        $urls = [];

        $urlQuery = $this->serviceContainer->getUrlQuery();
        $urlMapper = $this->serviceContainer->getUrlDataObjectMapper();

        $urlDataObjects = $urlQuery->getUrlsByUserId($user_id);

        foreach ($urlDataObjects as $dataObject) {
            $urls[] = $urlMapper->mapFromDataObject($dataObject);
        }

        return $urls;
    }
}
