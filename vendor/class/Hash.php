<?php

class Hash extends Facade implements ArrayAccess
{

    public function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    public function get($key)
    {
        return $this->{$key};
    }

    public function getString($key)
    {
        return (string)$this->{$key};
    }

    public function load(array $data)
    {
        foreach ($data as $k => $v) $this->{$k} = $v;
    }

    public function set($key, $value)
    {
        return $this->{$key} = $value;
    }

    public function __get($key)
    {
        return isset($this->{$key}) ? $this->{$key} : null;
    }

}
