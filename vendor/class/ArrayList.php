<?php

class ArrayList implements Iterator, Countable, Serializable
{

    private $position = 0;
    private $array = [];

    /**
     * ArrayList constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * @param int $key
     * @return mixed|null
     */
    public function getItem($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    /**
     * @param mixed $item
     * @return ArrayList
     */
    public function putItem($item)
    {
        array_push($this->array, $item);
        return $this;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->count();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        foreach ($this->array as $k => $v) {
            if ($v instanceof Model) $v = $v->toArray();
            $this->array[$k] = $v;
        }
        return $this->array;
    }

    /**
     * @return false|string
     */
    public function serialize()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param mixed $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->array = json_decode($serialized, true);
    }
}