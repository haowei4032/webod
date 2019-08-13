<?php

namespace model;
use Model;

class HashModel extends Model
{
    protected $tableName = 'wd_hash';

    public function getAttribute($name, $value)
    {
        if ($name === 'value') {
            $newValue = json_decode($value, true);
            return json_last_error() ? $value : $newValue;
        }
        if ($name === 'create_time') {
            return intval($value);
        }
        return parent::getAttribute($name, $value);
    }

    public function setAttribute($name, $value)
    {
        if ($name === 'value') {
            return is_array($value) || is_object($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
        }
        return parent::setAttribute($name, $value);
    }

}