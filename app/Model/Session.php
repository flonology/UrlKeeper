<?php
namespace Model;

/**
 * Class Session
 * @package Model
 */
class Session
{
    /** @var SessionWrapperInterface */
    private $sessionWrapper;

    /** @var KeyValueStore */
    private $keyValueStore;

    /** @var bool */
    private $started = false;

    /**
     * @param SessionWrapperInterface $sessionWrapper
     * @param KeyValueStore $keyValueStore
     */
    public function __construct(
        SessionWrapperInterface $sessionWrapper,
        KeyValueStore $keyValueStore
    ) {
        $this->sessionWrapper = $sessionWrapper;
        $this->keyValueStore = $keyValueStore;
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->started = $this->sessionWrapper->sessionStart();
        $this->keyValueStore->setValues($this->sessionWrapper->getValues());
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        if ($this->started == false) {
            throw new SessionException('Session not stated yet. Call start() first.');
        }

        $this->keyValueStore->set($key, $value);
        return $this;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->keyValueStore->get($key);
    }

    /**
     * @return $this
     */
    public function save()
    {
        $this->sessionWrapper->save($this->keyValueStore->getValues());
        $this->sessionWrapper->sessionRegenrateId();
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->sessionWrapper->sessionName($name);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->sessionWrapper->sessionName('');
    }

    /**
     * @return $this
     */
    public function close()
    {
        $this->keyValueStore->setValues([]);
        $this->sessionWrapper->close();
        return $this;
    }
}
