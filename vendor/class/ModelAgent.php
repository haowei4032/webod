<?php

class ModelAgent extends Model
{
    /**
     * @param string $tableName
     * @return Model
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
        return $this;
    }
}
