<?php

class ArrayList implements Iterator, Countable {
    
    private $position = 0;
    private $array = [];

    public function __construct() {
        $this->position = 0;
    }

    /**
     * @param mixed ...$value
     */
    public function append(...$value)
    {
        foreach (func_get_args() as $v)
        array_push($this->array, $v);
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }

    public function count()
    {
        return count($this->array);
    }

    public function isEmpty()
    {
        return !$this->count();
    }
}