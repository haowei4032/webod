<?php

abstract class Facade
{
    abstract protected function init(...$argv);

    /**
     * Facade constructor.
     * @param mixed ...$argv
     */
    private function __construct(...$argv)
    {
        $this->init($argv);
    }

    /**
     * @param mixed ...$argv
     * @return mixed
     */
    public static function getInstance(...$argv)
    {
        static $instance;
        return $instance ? $instance : ($instance = new static($argv));
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([static::getInstance(), $name], $arguments);
    }
}