<?php
namespace UrlKeeperTests;
use TestHelper\UrlKeeperTestCase;

use DateTime;
use Mapper\UrlDataObjectMapper;
use Mapper\UrlFormMapper;
use Model\DataObject\UrlDataObject;
use Model\Request;


class UrlMapperTest extends UrlKeeperTestCase
{
    /** @var UrlDataObjectMapper */
    private $urlDataObjectMapper;

    /** @var UrlFormMapper */
    private $urlFormMapper;

    protected function setUp()
    {
        $this->urlDataObjectMapper = new UrlDataObjectMapper();
        $this->urlFormMapper = new UrlFormMapper();
    }

    public function testDbMapperMapsFromAndToDataObject()
    {
        $now = new DateTime();
        $dataObject = $this->createExampleUrlDataObject(1, 7, $now);

        $url = $this->urlDataObjectMapper->mapFromDataObject($dataObject);
        $mappedDataObject = $this->urlDataObjectMapper->mapToDataObject($url);

        $this->assertInstanceOf('\Model\Url', $url);
        $this->assertEquals($dataObject, $mappedDataObject);
    }

    public function testFormMapperMapsToDataObject()
    {
        $now = new \DateTime();
        $dataObject = $this->createExampleUrlDataObject(1, 7, $now);

        $get = [
            'id' => $dataObject->id
        ];

        $post = [
            'url' => $dataObject->url,
            'title' => $dataObject->title,
            'description' => $dataObject->description
        ];

        $request = new Request($get, $post);
        $mappedDataObject = $this->urlFormMapper->mapToDataObject($request, 7);
        $this->urlFormMapper->initValues($mappedDataObject, $now);

        $this->assertNotEmpty($dataObject->created);
        $this->assertNotEmpty($dataObject->updated);
        $this->assertEquals($dataObject, $mappedDataObject);
    }

    public function testFormMapperMapsToDataObjectAndCreatedCurrentDate()
    {
        $now = new DateTime();
        $dataObject = $this->createExampleUrlDataObject(1, 7, $now);

        $get = [];
        $post = [
            'id' => $dataObject->id,
            'url' => $dataObject->url,
            'title' => $dataObject->title,
            'description' => $dataObject->description,
            // No created and updated date here
        ];

        $request = new Request($get, $post);
        $mappedDataObject = $this->urlFormMapper->mapToDataObject($request, 7);
        $this->urlFormMapper->initValues($mappedDataObject, $now);

        $this->assertNotEmpty($mappedDataObject->created, 'The current date should have been used.');
        $this->assertNotEmpty($mappedDataObject->updated, 'The current date should have been used.');
    }

    /**
     * @param string $id
     * @param string $user_id
     * @param DateTime $now
     * @return UrlDataObject
     */
    private function createExampleUrlDataObject($id, $user_id, DateTime $now)
    {
        $url = new UrlDataObject();
        $url->id = $id;
        $url->url = 'http://www.example.com/' . uniqid();
        $url->userId = $user_id;
        $url->title = 'Some Title ' . uniqid();
        $url->description = 'Some Description ' . uniqid();
        $url->created = $now->format('c');
        $url->updated = $now->format('c');

        return $url;
    }
}
