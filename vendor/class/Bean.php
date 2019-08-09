<?php

class Bean extends Facade
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    public function set($name)
    {
        return $this->name;
    }

    public function get()
    {

    }
}