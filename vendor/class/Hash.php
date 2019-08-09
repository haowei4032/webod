<?php

class Hash extends Facade implements ArrayAccess
{

    public function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return $this|void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
        return $this;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->{$key};
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @return string
     */
    public function getString($key, $defaultValue = null)
    {
        return strval(isset($this->{$key}) ? $this->{$key} : $defaultValue);
    }

    /**
     * @param array $data
     */
    public function load(array $data)
    {
        foreach ($data as $k => $v) $this->{$k} = $v;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->{$key} = $value;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return isset($this->{$key}) ? $this->{$key} : null;
    }

}
