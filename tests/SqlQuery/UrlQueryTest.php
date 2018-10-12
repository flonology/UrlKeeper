<?php
namespace UrlKeeperTests;
use Model\DataObject\UrlDataObject;
use Service\PdoSqlite;
use SqlQuery\UrlQuery;
use TestHelper\Db;
use TestHelper\UrlKeeperTestCase;

class UrlQueryTest extends UrlKeeperTestCase
{
    /** @var PdoSqlite */
    private $pdo;

    /** @var UrlQuery */
    private $urlQuery;

    protected function setUp()
    {
        $this->pdo = Db::initDb();
        $this->urlQuery = new UrlQuery($this->pdo);
    }

    public function testCreateNewUrlInDb()
    {
        $urlA = $this->createExampleUrlDataObject(null, 1);
        $urlB = $this->createExampleUrlDataObject(null, 2);

        $createdA = $this->urlQuery->createUrl($urlA);
        $createdB = $this->urlQuery->createUrl($urlB);

        $allUrls = $this->urlQuery->getAll();
        $this->assertCount(2, $allUrls);

        $this->assertEquals($allUrls[0], $createdA);
        $this->assertEquals($allUrls[1], $createdB);
    }

    public function testGetUrlByIdAndUserIdFromDb()
    {
        $url = $this->createExampleUrlDataObject(null, 1);
        $created = $this->urlQuery->createUrl($url);

        $result = $this->urlQuery->getUrlByIdAndUserId($created->id, 1);
        $this->assertEquals($created, $result);
    }

    public function testGetUrlsByUserId()
    {
        $examples = [];
        $examples[] = $this->createExampleUrlDataObject(null, 1);
        $examples[] = $this->createExampleUrlDataObject(null, 1);
        $examples[] = $this->createExampleUrlDataObject(null, 1);
        $examples[] = $this->createExampleUrlDataObject(null, 2);

        $created = [];
        $created[] = $this->urlQuery->createUrl($examples[0]);
        $created[] = $this->urlQuery->createUrl($examples[1]);
        $created[] = $this->urlQuery->createUrl($examples[2]);
        $created[] = $this->urlQuery->createUrl($examples[3]);

        $byUser = $this->urlQuery->getUrlsByUserId(1);
        $this->assertCount(3, $byUser);
        $this->assertEquals($byUser[0], $created[0]);
        $this->assertEquals($byUser[1], $created[1]);
        $this->assertEquals($byUser[2], $created[2]);

        $byUser = $this->urlQuery->getUrlsByUserId(2);
        $this->assertCount(1, $byUser);
        $this->assertEquals($byUser[0], $created[3]);
    }

    public function testGetUrlByIdAndNonExistentUserIdFromDbReturnsNull()
    {
        $url = $this->createExampleUrlDataObject(null, 1);
        $created = $this->urlQuery->createUrl($url);

        $result = $this->urlQuery->getUrlByIdAndUserId($created->id, 1);
        $this->assertNotEmpty($result);

        $result = $this->urlQuery->getUrlByIdAndUserId($created->id, 2);
        $this->assertNull($result, 'Getting non existent url from db should return null.');
    }

    public function testUpdateUrl()
    {
        $url = $this->createExampleUrlDataObject(null, 1);
        $created = $this->urlQuery->createUrl($url);

        $created->title = 'Another title';
        $created->url = 'http://example.com/another-example';
        $created->description = 'Another description';
        $created->updated = date('c');

        $this->urlQuery->updateUrl($created);
        $updated = $this->urlQuery->getUrl($created->id);

        $this->assertEquals($created, $updated);
    }

    public function testTrashUrlByIdAndUserId()
    {
        $user_id = 1;

        $url = $this->createExampleUrlDataObject(null, $user_id);
        $created = $this->urlQuery->createUrl($url);

        $this->urlQuery->trashUrlByIdAndUserId($created->id, $user_id);
        $trashed = $this->urlQuery->getUrl($created->id);

        $created->trashed = 1;
        $this->assertEquals($created, $trashed);
    }

    public function testUnTrashUrlByIdAndUserId()
    {
        $user_id = 1;

        $url = $this->createExampleUrlDataObject(null, $user_id);
        $created = $this->urlQuery->createUrl($url);

        $this->urlQuery->trashUrlByIdAndUserId($created->id, $user_id);
        $this->urlQuery->unTrashUrlByIdAndUserId($created->id, $user_id);
        $unTrashed = $this->urlQuery->getUrl($created->id);

        $created->trashed = 0;
        $this->assertEquals($created, $unTrashed);
    }

    public function testEmptyTrash()
    {
        $user_id = 1;

        // Not in trash
        $url = $this->createExampleUrlDataObject(null, $user_id);
        $this->urlQuery->createUrl($url);

        // In trash
        $url = $this->createExampleUrlDataObject(null, $user_id);
        $created = $this->urlQuery->createUrl($url);
        $this->urlQuery->trashUrlByIdAndUserId($created->id, $user_id);

        // In trash
        $url = $this->createExampleUrlDataObject(null, $user_id);
        $created = $this->urlQuery->createUrl($url);
        $this->urlQuery->trashUrlByIdAndUserId($created->id, $user_id);

        $this->assertCount(2, $this->urlQuery->getTrashedUrlsByUserId($user_id));

        $this->urlQuery->emptyTrashByUserId($user_id);
        $this->assertCount(0, $this->urlQuery->getTrashedUrlsByUserId($user_id));
        $this->assertCount(1, $this->urlQuery->getUrlsByUserId($user_id));
    }

    /**
     * @param string $id
     * @param string $user_id
     * @return UrlDataObject
     */
    private function createExampleUrlDataObject($id, $user_id)
    {
        $now = new \DateTime();

        $url = new UrlDataObject();
        $url->id = $id;
        $url->url = 'http://www.example.com/' . uniqid();
        $url->userId = $user_id;
        $url->title = 'Some Title ' . uniqid();
        $url->description = 'Some Description ' . uniqid();
        $url->created = $now->format('c');
        $url->updated = $now->format('c');
        $url->trashed = 0;

        return $url;
    }
}
