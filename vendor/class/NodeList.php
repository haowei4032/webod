<?php

class NodeList implements Iterator {
    private $position = 0;
    private $array = [];

    public function __construct() {
        $this->position = 0;
    }

    public function append($value)
    {
        array_push($this->array, $value);
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
}