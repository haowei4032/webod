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

    /**
     * @param string $sql
     * @param array $input
     * @return bool
     */
    public static function exec($sql, $input = null)
    {
        if (!getPdo()) return false;
        return getPdo()->prepare($sql)->execute($input);
    }

    /**
     * @param Model $model
     */
    public static function pushQueryLog(Model $model)
    {
        self::$queryLog[] = $model->getBuildSql();
    }

    /**
     * @param string $tableName
     * @return mixed|null
     */
    public static function getPrimaryKey($tableName)
    {
        if (!getPdo()) return null;
        try {
            $sth = getPdo()->prepare('show fields from `' . $tableName . '` where `Key` = ?');
            $sth->execute(['PRI']);
        } catch (PDOException $ex) {
            return null;
        }
        return $sth->fetchColumn();
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