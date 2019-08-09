<?php

class Cookie extends Facade
{
	private $queue = [];

	protected function init()
    {
        // TODO: Implement init() method.
    }

    public function flush()
	{
		foreach ($this->queue as $cookie)
		setcookie($cookie['name'], $cookie['value']);
	}

	public function get($name)
	{
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}

	public function set($name, $value, $path = '/', $expire = null, $domain = null, $readonlyHttp = false)
	{

	}
}