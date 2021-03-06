<?php

abstract class Model implements ArrayAccess
{
    protected $primaryKey = null;
    protected $tableName = null;
    private $buildSql = null;

    private $_attributes = null;
    private $_prepareAttributes = null;
    private $fields = '*';
    private $limit = null;

    private $subWhere = null;
    private $whereGroup = [];
    private $whereParameter = [];
    private $lastInsertId = null;

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function getAttribute($name, $value)
    {
        return isset($this->_attributes[$name]) ? $value : null;
    }

    /**
     * @param $name
     * @param $value
     * @return Model
     */
    public function setAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
        return $value;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_attributes[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->_attributes[$offset]) ? $this->getAttribute($offset, $this->_attributes[$offset]) : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_attributes[$offset] = $this->setAttribute($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_attributes[$offset]);
    }

    /**
     * @return array|null
     */
    public function toArray()
    {
        return $this->_attributes;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->_attributes[$name]) ? $this->getAttribute($name, $this->_attributes[$name]) : null;
    }

    public function __set($name, $value)
    {
        $this->_prepareAttributes[$name] = $value;
        return $this;
    }

    /**
     * @param string|array $select
     * @return $this
     */
    public function select(array $select)
    {
        if (is_array($select)) $this->fields = implode(',', $select);
        return $this;
    }

    /**
     * @param mixed $condition
     * @return $this
     */
    public function where($condition)
    {
        /*if ($this->whereGroup) $this->whereGroup[] = 'and';*/
        if (!$this->subWhere) if ($this->whereGroup) $this->whereGroup[] = 'and';

        if (is_array($condition)) {
            $whereGroup = [];
            $fields = array_keys($condition);
            for ($i = 0; $i < (count($fields) - 1); $i++) {
                $operator = [
                    '`' . $fields[$i] . '`',
                    '=',
                    '?'
                ];
                $whereGroup[] = implode(' ', $operator);
                $this->whereParameter[] = $condition[$fields[$i]];
            }
            $this->whereGroup[] = '(' . implode(' and ', $whereGroup) . ')';
        } elseif (!is_string($condition) && is_callable($condition)) {
            $this->subWhere = [];
            call_user_func($condition, $this);
            $this->whereGroup[] = '(' . implode(' and ', $this->subWhere) . ')';
            $this->subWhere = null;
        } else {
            $operator = [
                '`' . $condition . '`',
                '=',
                '?'
            ];
            if (func_num_args() === 2) {
                $this->whereParameter[] = func_get_arg(1);
            } elseif (func_num_args() === 3) {
                $operator[1] = func_get_arg(1);
                $this->whereParameter[] = func_get_arg(2);
            }

            $operator[1] = $this->formatOperator($operator[1]);

            if (is_null($this->subWhere)) {
                $this->whereGroup[] = implode(' ', $operator);
            } else {
                $this->subWhere[] = implode(' ', $operator);
            }
        }
        return $this;
    }

    /**
     * @param string $className
     * @param string $localKey
     * @param string $foreignKey
     * @return mixed
     */
    public function belongsTo($className, $localKey, $foreignKey)
    {

    }

    /**
     * @param string $className
     * @param string $localKey
     * @param string $foreignKey
     * @return mixed
     */
    public function hasOne($className, $localKey = null, $foreignKey = null)
    {
        $singleInstance = call_user_func([$className, 'getInstance']);
        return $singleInstance->where($foreignKey, $this->_attributes[$localKey])->first();
    }

    /**
     * @param string $className
     * @param string $localKey
     * @param string $foreignKey
     * @return mixed
     */
    public function hasMany($className, $localKey = null, $foreignKey = null)
    {
        $singleInstance = call_user_func([$className, 'getInstance']);
        return $singleInstance->where($foreignKey, $this->_attributes[$localKey])->get();
    }

    /**
     * @param string $operator
     * @return string
     */
    private function formatOperator($operator)
    {
        switch ($operator) {
            case 'lt':
            case '$lt':
                return '<';
            case 'lte':
            case '$lte':
                return '<=';
            case 'gt':
            case '$gt':
                return '>';
            case 'gte':
            case '$gte':
                return '>=';
            case 'eq':
            case '$eq':
                return '=';
            case 'ne':
            case 'neq':
            case '$ne':
            case '$neq':
                return '<>';
            default:
                return $operator;
        }
    }

    /**
     * @param mixed $condition
     * @return $this
     */
    public function whereOr($condition)
    {
        if ($this->whereGroup) $this->whereGroup[] = 'or';
        if ($this->subWhere) $this->subWhere[] = 'or';
        if (is_array($condition)) {
            $whereGroup = [];
            $fields = array_keys($condition);
            for ($i = 0; $i < (count($fields) - 1); $i++) {
                $operator = [
                    '`' . $fields[$i] . '`',
                    '=',
                    '?'
                ];
                $whereGroup[] = implode(' ', $operator);
                $this->whereParameter[] = $condition[$fields[$i]];
            }
            $this->whereGroup[] = '(' . implode(' or ', $whereGroup) . ')';
        } elseif (!is_string($condition) && is_callable($condition)) {
            $this->subWhere = [];
            call_user_func($condition, $this);
            $this->whereGroup[] = '(' . implode(' and ', $this->subWhere) . ')';
            $this->subWhere = null;
        } else {
            $operator = [
                '`' . $condition . '`',
                '=',
                '?'
            ];
            if (func_num_args() === 2) {
                $this->whereParameter[] = func_get_arg(1);
            } elseif (func_num_args() === 3) {
                $operator[1] = func_get_arg(1);
                $this->whereParameter[] = func_get_arg(2);
            }

            $operator[1] = $this->formatOperator($operator[1]);

            if (is_null($this->subWhere)) {
                $this->whereGroup[] = implode(' ', $operator);
            } else {
                $this->subWhere[] = implode(' ', $operator);
            }
        }
        return $this;
    }

    /**
     * @param string $field
     * @param array $value
     * @return $this
     */
    public function whereIn($field, $value)
    {
        if (!$this->subWhere) $this->whereGroup[] = 'and';
        if ($this->subWhere) {
            $this->subWhere[] = '`' . $field . '` in (' . implode(',', $value) . ')';
        } else {
            $this->whereGroup[] = '`' . $field . '` in (' . implode(',', $value) . ')';
        }

        return $this;
    }

    /**
     * @param string $field
     * @param array $value
     * @return $this
     */
    public function whereNotIn($field, $value)
    {
        if (!$this->subWhere) $this->whereGroup[] = 'and';
        if ($this->subWhere) {
            $this->subWhere[] = '`' . $field . '` not in (' . implode(',', $value) . ')';
        } else {
            $this->whereGroup[] = '`' . $field . '` not in (' . implode(',', $value) . ')';
        }

        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function whereNull($field)
    {
        if (!$this->subWhere) $this->whereGroup[] = 'and';
        if ($this->subWhere) {
            $this->subWhere[] = '`' . $field . '` is null';
        } else {
            $this->whereGroup[] = '`' . $field . '` is null';
        }
        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function whereNotNull($field)
    {
        if (!$this->subWhere) $this->whereGroup[] = 'and';
        if ($this->subWhere) {
            $this->subWhere[] = '`' . $field . '` is not null';
        } else {
            $this->whereGroup[] = '`' . $field . '` is not null';
        }
        return $this;
    }

    /**
     * @param int ...$num
     * @return $this
     */
    public function limit(...$num)
    {
        $this->limit = implode(',', func_get_args());
        return $this;
    }

    /**
     * @return Generator
     */
    public function getForIterator()
    {
        $this->buildSql = trim(strtr('select {field} from `{tableName}` where {where} {limit}', [
            '{field}' => $this->fields,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1,
            '{limit}' => $this->limit ? 'limit ' . $this->limit : ''
        ]));
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        while (($rows = $sth->fetch()) !== false) {
            foreach ($rows as $k => $v) $this->_attributes[$k] = $v;
            yield $this;
        }
    }

    /**
     * @return ArrayList
     */
    public function get()
    {
        $this->buildSql = trim(strtr('select {field} from `{tableName}` where {where} {limit}', [
            '{field}' => $this->fields,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1,
            '{limit}' => $this->limit ? 'limit ' . $this->limit : ''
        ]));
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $result = $sth->fetchAll();
        $list = new ArrayList();
        foreach ($result as $next => $rows) {
            $object = clone $this;
            foreach ($rows as $k => $v) $object->_attributes[$k] = $v;
            $list->putItem($object);
        }
        //call_user_func_array([$list, 'append'], $result);
        $this->reset();
        return $list;
    }

    /**
     * @param int $pageSize
     * @return array
     */
    public function paginate($pageSize)
    {
        $page = getRequest()->getInt('page', 1, 1);
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit {offset}, {pageSize}', $input = [
            '{field}' => $this->fields,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1,
            '{offset}' => ($page - 1) * $pageSize,
            '{pageSize}' => intval($pageSize)
        ]);
        $this->limit = implode(',', [$input['{offset}'], $input['{pageSize}']]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $list = $sth->fetchAll();
        $total = $this->count();
        $this->reset();
        return [
            'total' => $total,
            'page' => $page,
            'page_size' => $pageSize,
            'list' => $list
        ];
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function to($field)
    {
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => '`' . $field . '`',
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);

        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $value = $sth->fetchColumn();
        $this->reset();
        return $value;
    }

    /**
     * @param string|array $field
     * @return mixed
     */
    public function sum($field)
    {
        $retType = 'int';
        if (is_array($field)) {
            $retType = 'array';
            $fieldDup = [];
            foreach ($field as $v) $fieldDup[] = 'SUM(`' . $v . '`) as `' . $v . '`';
            $field = implode(',', $fieldDup);
        } else {
            $field = 'SUM(`' . $field . '`) as `' . $field . '`';
        }
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => $field,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        if ($retType === 'array') {
            $value = $sth->fetch();
        } else {
            $value = $sth->fetchColumn();
        }
        $this->reset();
        return $value;
    }

    /**
     * @param array|string $field
     * @return mixed
     */
    public function avg($field)
    {
        $retType = 'int';
        if (is_array($field)) {
            $retType = 'array';
            $fieldDup = [];
            foreach ($field as $v) $fieldDup[] = 'AVG(`' . $v . '`) as `' . $v . '`';
            $field = implode(',', $fieldDup);
        } else {
            $field = 'AVG(`' . $field . '`) as `' . $field . '`';
        }
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => $field,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        if ($retType === 'array') {
            $value = $sth->fetch();
        } else {
            $value = $sth->fetchColumn();
        }
        $this->reset();
        return $value;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function max($field)
    {
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => 'MAX(`' . $field . '`) as `' . $field . '`',
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $value = $sth->fetchColumn();
        $this->reset();
        return $value;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function min($field)
    {
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => 'MIN(`' . $field . '`) as `' . $field . '`',
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $value = $sth->fetchColumn();
        $this->reset();
        return $value;
    }

    /**
     * @return int
     */
    public function count()
    {
        $this->buildSql = strtr('select count(*) from `{tableName}` where {where} limit 1', [
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $value = $sth->fetchColumn();
        $this->reset();
        return intval($value);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $this->buildSql = strtr('select {field} from `{tableName}` where {where} limit 1', [
            '{field}' => $this->fields,
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' ', $this->whereGroup) : 1
        ]);

        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $rows = $sth->fetch();
        $this->reset();
        if (!$rows) return null;
        $object = clone $this;
        foreach ($rows as $k => $v) $object->_attributes[$k] = $v;
        return $object;
    }

    /**
     * @param bool $check
     * @return bool
     */
    public function save($check = false)
    {
        if (!$this->_attributes) {
            return $this->insert($this->_prepareAttributes);
        } else {
            if (isset($this->primaryKey, $this->_attributes[$this->primaryKey])) {
                return $this->where($this->primaryKey, $this->_attributes[$this->primaryKey])->update($this->_prepareAttributes);
            } else {
                foreach ($this->_attributes as $key => $value) {
                    $this->where($key, $value);
                }
                return $this->update($this->_prepareAttributes);
            }
        }
    }

    /**
     * @return bool
     */
    public function exist()
    {
        $this->buildSql = strtr('select count(*) from `{tableName}` where {where} limit 1', [
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $sth->execute($this->whereParameter);
        $this->reset();
        return $sth->rowCount() > 0;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data)
    {
        foreach ($data as $k => $v) {
            $data[$k] = $this->setAttribute($k, $v);
        }
        $this->buildSql = strtr('insert into `{tableName}` ({fieldGroup}) values ({placeholder})', [
            '{tableName}' => $this->tableName,
            '{fieldGroup}' => implode(',', array_map(function ($key) {
                return '`' . $key . '`';
            }, array_keys($data))),
            '{placeholder}' => implode(',', array_fill(0, count($data), '?'))
        ]);
        DB::pushQueryLog($this);
        $bool = getPdo()->prepare($this->buildSql)->execute(array_values($data));
        $this->lastInsertId = getPdo()->lastInsertId();
        $this->reset();
        return $bool;
    }

    /**
     * @param array $data
     * @return int|null
     */
    public function insertGetId(array $data)
    {
        $this->insert($data);
        return $this->lastInsertId;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        foreach ($data as $k => $v) {
            $data[$k] = $this->setAttribute($k, $v);
        }

        $this->buildSql = strtr('update `{tableName}` set {fieldGroup} where {where}', [
            '{tableName}' => $this->tableName,
            '{fieldGroup}' => implode(',', array_map(function ($key) {
                return '`' . $key . '` = ?';
            }, array_keys($data))),
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $sth = getPdo()->prepare($this->buildSql);
        $bool = $sth->execute(array_merge(array_values($data), $this->whereParameter));
        $this->reset();
        return $bool;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateOrNew(array $data)
    {
        $self = clone $this;
        $rows = $self->first();
        return $rows ? $this->update($data) : $this->insert($data);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->buildSql = strtr('delete from `{tableName}` where {where}', [
            '{tableName}' => $this->tableName,
            '{where}' => $this->whereGroup ? implode(' and ', $this->whereGroup) : 1
        ]);
        DB::pushQueryLog($this);
        $bool = getPdo()->prepare($this->buildSql)->execute($this->whereParameter);
        $this->reset();
        return $bool;
    }

    /**
     * @return string|null
     */
    public function getBuildSql()
    {
        return $this->buildSql;
    }

    public function reset()
    {
        if ($this->_prepareAttributes) {
            $this->_attributes  = array_merge($this->_attributes, $this->_prepareAttributes);
            $this->_prepareAttributes = null;
        } else {
            $this->_attributes = null;
            $this->fields = '*';

            $this->subWhere = null;
            $this->whereGroup = [];
            $this->whereParameter = [];
            //$this->lastInsertId = null;
        }
    }

    public function __destruct()
    {
        $this->reset();
    }
}