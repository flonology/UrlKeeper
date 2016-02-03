<?php
namespace Model;
use DateTime;


class Url
{
    /** @var int */
    private $id = null;

    /** @var int */
    private $user_id = null;

    /** @var string */
    private $url = '';

    /** @var string */
    private $title = '';

    /** @var string */
    private $description = '';

    /** @var DateTime */
    private $created = null;

    /** @var DateTime */
    private $updated = null;


    /**
     * @param int $id
     * @param int $user_id
     * @param string $url
     * @param string $title
     * @param string $description
     * @param DateTime $created
     * @param DateTime $updated
     */
    public function __construct(
        $id,
        $user_id,
        $url,
        $title,
        $description,
        DateTime $created,
        DateTime $updated
    )
    {
        if (is_int($user_id) === false || $user_id < 1) {
            throw new \RuntimeException('User id must be an integer bigger than 0.');
        }

        $this->id = $id;
        $this->user_id = $user_id;
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
