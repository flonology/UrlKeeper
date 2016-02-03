<?php
namespace SqlQuery;
use Model\DataObject\UrlDataObject;
use Service\PdoSqlite;


class UrlQuery
{
    /** @var PdoSqlite */
    private $db;

    /**
     * @param PdoSqlite $db
     */
    public function __construct(PdoSqlite $db)
    {
        $this->db = $db;
    }

    /**
     * @return UrlDataObject[]
     */
    public function getAll()
    {
        $query = '
            SELECT ' . $this->getSelectColumns() . '
            FROM urls
        ';

        $results = $this->db->exec($query);
        $dataObjects = [];

        foreach ($results as $result) {
            $dataObjects[] = $this->createUrlDataObject($result);
        }

        return $dataObjects;
    }

    /**
     * @param int $id
     * @return UrlDataObject | null
     */
    public function getUrl($id)
    {
        $query = '
            SELECT ' . $this->getSelectColumns() . '
            FROM urls WHERE id = :id
        ';

        $results = $this->db->exec($query, [':id' => $id]);
        if (empty($results)) {
            return null;
        }

        $result = $results[0];
        return $this->createUrlDataObject($result);
    }

    /**
     * @param int $user_id
     * @return UrlDataObject[]
     */
    public function getUrlsByUserId($user_id)
    {
        $query = '
            SELECT ' . $this->getSelectColumns() . '
            FROM urls
            WHERE user_id = :user_id
            AND trashed = 0
            ORDER BY updated DESC
        ';

        $results = $this->db->exec($query, [':user_id' => $user_id]);
        $dataObjects = [];

        foreach ($results as $result) {
            $dataObjects[] = $this->createUrlDataObject($result);
        }

        return $dataObjects;
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return UrlDataObject | null
     */
    public function getUrlByIdAndUserId($id, $user_id)
    {
        $query = '
            SELECT ' . $this->getSelectColumns() . '
            FROM urls
            WHERE id = :id
            AND user_id = :user_id
        ';

        $results = $this->db->exec($query, [
            ':id' => $id,
            ':user_id' => $user_id
        ]);

        if (empty($results)) {
            return null;
        }

        $result = $results[0];
        return $this->createUrlDataObject($result);
    }

    /**
     * @param UrlDataObject $urlDataObject
     * @return array
     */
    public function updateUrl(UrlDataObject $urlDataObject)
    {
        if ($urlDataObject->id == null) {
            throw new \RuntimeException('Cannot update URL without id.');
        }

        $query = '
            UPDATE urls SET
                url = :url,
                title = :title,
                description = :description,
                updated = :updated
            WHERE id = :id
            AND user_id = :user_id
        ';

        return $this->db->exec($query, [
            ':url' => $urlDataObject->url,
            ':title' => $urlDataObject->title,
            ':description' => $urlDataObject->description,
            ':updated' => $urlDataObject->updated,
            ':id' => $urlDataObject->id,
            ':user_id' => $urlDataObject->userId
        ]);
    }

    /**
     * @param UrlDataObject $urlDataObject
     * @return UrlDataObject (with newly created id)
     */
    public function createUrl(UrlDataObject $urlDataObject)
    {
        $query = '
            INSERT INTO urls (user_id, url, title, description, created, updated)
            VALUES (:user_id, :url, :title, :description, :created, :updated)
        ';

        $this->db->exec($query, [
            ':user_id' => $urlDataObject->userId,
            ':url' => $urlDataObject->url,
            ':title' => $urlDataObject->title,
            ':description' => $urlDataObject->description,
            ':created' => $urlDataObject->created,
            ':updated' => $urlDataObject->updated
        ]);

        $created = clone $urlDataObject;
        $created->id = $this->db->getLastInsertId();

        return $created;
    }

    /**
     * @param $result
     * @return UrlDataObject
     */
    private function createUrlDataObject($result)
    {
        $dataObject = new UrlDataObject();
        $dataObject->id = $result['id'];
        $dataObject->userId = $result['user_id'];
        $dataObject->url = $result['url'];
        $dataObject->title = $result['title'];
        $dataObject->description = $result['description'];
        $dataObject->created = $result['created'];
        $dataObject->updated = $result['updated'];
        $dataObject->trashed = $result['trashed'];
        return $dataObject;
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function trashUrlByIdAndUserId($id, $user_id)
    {
        $query = '
            UPDATE urls SET
                trashed = 1
            WHERE id = :id
            AND user_id = :user_id
        ';

        return $this->db->exec($query, [
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function unTrashUrlByIdAndUserId($id, $user_id)
    {
        $query = '
            UPDATE urls SET
                trashed = 0
            WHERE id = :id
            AND user_id = :user_id
        ';

        return $this->db->exec($query, [
            ':id' => $id,
            ':user_id' => $user_id
        ]);
    }

    /**
     * @param $user_id
     * @return UrlDataObject[]
     */
    public function getTrashedUrlsByUserId($user_id)
    {
        $query = '
            SELECT ' . $this->getSelectColumns() . '
            FROM urls
            WHERE user_id = :user_id
            AND trashed = 1
            ORDER BY updated DESC
        ';

        $results = $this->db->exec($query, [':user_id' => $user_id]);
        $dataObjects = [];

        foreach ($results as $result) {
            $dataObjects[] = $this->createUrlDataObject($result);
        }

        return $dataObjects;
    }

    /**
     * @return string
     */
    private function getSelectColumns()
    {
        return 'id, user_id, url, title, description, created, updated, trashed';
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function emptyTrashByUserId($user_id)
    {
        $query = '
            DELETE FROM urls
            WHERE user_id = :user_id
            AND trashed = 1
        ';

        return $this->db->exec($query, [
            ':user_id' => $user_id
        ]);
    }
}
