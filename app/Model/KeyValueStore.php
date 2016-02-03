<?php
namespace Model;

/**
 * Class KeyValueStore
 * @package flonology
 */
class KeyValueStore
{
    /** @var array */
    private $values = [];

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }
}
