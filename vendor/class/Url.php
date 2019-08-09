<?php

class Url extends Facade
{
    protected function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    /**
     * @param $path
     * @return string
     */
    public function assets($path)
    {
        return getRequest()->getSubPath() . '/assets/' . $path;
    }

    /**
     * @param $path
     * @return string
     */
    public function to($path)
    {
        return getRequest()->getSubPath() . $path;
    }

    /**
     * @param $path
     * @return void
     */
    public function redirect($path)
    {
        header('Location: ' . $path);
        echo '<!doctype html><html><head>301 Moved Permanently</head><body>301 Moved Permanently</body></html>';
        exit(0);
    }
}