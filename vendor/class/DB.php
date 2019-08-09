<?php

class DB extends Facade
{
    private static $queryLog = [];

    protected function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    /**
     * @return mixed
     */
    public static function enableQueryLog()
    {
        self::$queryLog = [];
    }

    public static function pushQueryLog(Model $model)
    {
        self::$queryLog[] = $model->getBuildSql();
    }

    /**
     * @return array
     */
    public static function getQueryLog()
    {
        return self::$queryLog;
    }

    /**
     * @return bool
     */
    public static function beginTransaction()
    {
        return getPdo() && getPdo()->beginTransaction();
    }

    /**
     * @return bool
     */
    public static function inTransaction()
    {
        return getPdo() && getPdo()->inTransaction();
    }

    /**
     * @return bool
     */
    public static function commit()
    {
        return getPdo() && getPdo()->commit();
    }

    /**
     * @return bool
     */
    public static function rollBack()
    {
        return getPdo() && getPdo()->rollBack();
    }

}