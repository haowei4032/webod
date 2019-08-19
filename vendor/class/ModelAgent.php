<?php

class ModelAgent extends Model
{
    public function __construct($tableName)
    {
        call_user_func_array([$this, 'setTableName'], func_get_args());
    }

    /**
     * @param string $tableName
     * @return mixed
     */
    public function setTableName($tableName)
    {
        if (preg_match('/^[\$\%\#](.+)/', $tableName, $submatch)) {
            list($_, $tableName) = $submatch;
            $tablePrefix = getConstant('DB_TABLE_PREFIX');
            $this->tableName = $tablePrefix . $tableName;
        } else {
            $this->tableName = $tableName;
        }
        $this->primaryKey = DB::getPrimaryKey($this->tableName);
        return $this;
    }

}
