<?php

class HashAgent extends Facade
{
    private $history = [];
    private $map = [];

    /**
     * @param mixed ...$argv
     */
    protected function init(...$argv)
    {
        $this->load($argv);
    }

    /**
     * @param mixed ...$argv
     */
    public function load(...$argv)
    {
        if (getPdo()) {
            foreach (model\HashModel::getInstance()->get() as $k => $rows) {
                $this->history[$rows->key] = $rows->value;
                $this->set($rows->key, $rows->value, true);
            }
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $hash = Hash::getInstance();
        return call_user_func_array([$hash, __FUNCTION__], func_get_args());
    }

    /**
     * @param $key
     * @param $value
     * @param bool $save
     * @return mixed
     */
    public function set($key, $value, $save = false)
    {
        $hash = Hash::getInstance();
        if ($save) $this->map[$key] = $value;
        return call_user_func_array([$hash, __FUNCTION__], func_get_args());
    }

    /**
     * @return void
     */
    public function save()
    {
        DB::beginTransaction();
        foreach ($this->map as $key => $value) {
            if (isset($this->history[$key]) && $this->history[$key] === $value) continue;
            model\HashModel::getInstance()->where('key', $key)->updateOrNew([
                'key' => $key,
                'value' => $value,
                'create_time' => time()
            ]);
        }
        DB::commit();
    }

    /**
     *
     * @return string
     */
    public function random()
    {
        return md5(microtime(true));
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        static $hash;
        if (!$hash) $hash = Hash::getInstance();
        return call_user_func_array([$hash, $method], $arguments);
    }
}
