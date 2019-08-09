<?php

class Request extends Facade
{
    protected function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    public function has($name)
    {
        return isset($_REQUEST[$name]);
    }

    public function getInt($name, $defaultValue = 0, $minValue = -1)
    {
        $value = $this->get($name, $defaultValue);
        $value = !is_numeric($value) ? $defaultValue : intval($value);
        if ($value < $minValue) $value = $minValue;
        return $value;
    }

    public function get($name, $defaultValue = null)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $defaultValue;
    }

    /**
     * 获取cookie
     * @return Cookie|CookieAgent
     */
    public function getCookie()
    {
        return CookieAgent::getInstance();
    }

    public function getString($name, $defaultValue = null)
    {
        return strval($this->get($name, $defaultValue));
    }

    public function getRemoteAddr()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function getServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public function getDocumentRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    public function getSubPath()
    {
        return str_replace('\\', '/', substr(__ROOT__, strlen($_SERVER['DOCUMENT_ROOT'])));
    }

    public function getURLScheme()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
    }
}
