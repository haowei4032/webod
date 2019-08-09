<?php

class Logger extends Facade
{
    private $stack = [];

    protected function init()
    {

    }

    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @param $level
     * @param $tag
     * @param $message
     * @return mixed
     */
    private function format($level, $tag, $message)
    {
        return array_push($this->stack, [$level => implode('||', [
            date('r'),
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-',
            sprintf('%.3f', microtime(true)),
            isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '-',
            isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '-',
            isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '-',
            $level,
            $tag,
            $message
        ])]);
    }

    public function info($tag, $message = '-')
    {
        return $this->format(__FUNCTION__, $tag, $message);
    }

    public function warning($tag, $message = '-')
    {
        return $this->format(__FUNCTION__, $tag, $message);
    }

    public function error($tag, $message = '-')
    {
        return $this->format(__FUNCTION__, $tag, $message);
    }

    public function debug($tag, $message = '-')
    {
        return $this->format(__FUNCTION__, $tag, $message);
    }

}
