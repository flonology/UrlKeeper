<?php
namespace Model;


class SessionBuilder
{
    /** @var KeyValueStore */
    private $keyValueStore;

    /** @var SessionWrapperInterface */
    private $sessionWrapper;

    /**
     * @param string $name
     * @return Session
     */
    public function buildSession($name)
    {
        $session = new Session(
            $this->buildSessionWrapper(),
            $this->buildKeyValueStore()
        );

        $session->setName($name);
        return $session;
    }

    /**
     * @return KeyValueStore
     */
    public function buildKeyValueStore()
    {
        if ($this->keyValueStore) {
            return $this->keyValueStore;
        }

        $this->keyValueStore = new KeyValueStore();
        return $this->keyValueStore;
    }

    /**
     * @param KeyValueStore $keyValueStore
     * @return $this
     */
    public function setKeyValueStore($keyValueStore)
    {
        $this->keyValueStore = $keyValueStore;
        return $this;
    }

    /**
     * @return SessionWrapperInterface
     */
    public function buildSessionWrapper()
    {
        if ($this->sessionWrapper) {
            return $this->sessionWrapper;
        }

        $this->sessionWrapper = new SessionWrapper();
        return $this->sessionWrapper;
    }

    /**
     * @param SessionWrapperInterface $sessionWrapper
     * @return $this
     */
    public function setSessionWrapper($sessionWrapper)
    {
        $this->sessionWrapper = $sessionWrapper;
        return $this;
    }
}
