<?php

namespace System\Database\Traits;

use System\Database\DBConnection\DBConnection;

trait HasCRUD
{

    protected function createMethod($values)
    {

        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function updateMethod($values)
    {
        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function whereMethod($attribute, $firstValue, $secondValue = null)
    {
        if($secondValue === null){
            $condition = $this->getAttributeName($attribute).' = ?';
            $this->addValue($attribute, $firstValue);
        }
        else{
            $condition = $this->getAttributeName($attribute).' '.$firstValue.' ?';
            $this->addValue($attribute, $secondValue);
        }
        $operator = 'AND';
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;
    }

    protected function whereOrMethod($attribute, $firstValue, $secondValue = null)
    {
        if ($secondValue == null) {
            $condiction = $this->getAttributeName($attribute) . " =  ?";
            $this->addValue($attribute, $firstValue);
        } else {
            $condiction = $this->getAttributeName($attribute) . "  " .  $firstValue . "  ?";
            $this->addValue($attribute, $secondValue);
        }
        $operator = "OR";
        $this->setWhere($operator, $condiction);

        $this->setAllowedMethods([
            'where', 'whereOr', 'whereIn',
            'whereNull', 'whereNotNull', 'limit',
            'orderBy', 'get', 'paginate'
        ]);

        return $this;
    }
    protected function whereNullMethod($attribute)
    {
        $condiction = $this->getAttributeName($attribute) . " IS NULL ";
        $operator = "AND";
        $this->setWhere($operator, $condiction);
        $this->setAllowedMethods([
            'where', 'whereOr', 'whereIn',
            'whereNull', 'whereNotNull', 'limit',
            'orderBy', 'get', 'paginate'
        ]);

        return $this;
    }

    protected function whereNotNullMethod($attribute)
    {
        $condiction = $this->getAttributeName($attribute) . " IS NOT NULL ";
        $operator = "AND";
        $this->setWhere($operator, $condiction);
        $this->setAllowedMethods([
            'where', 'whereOr', 'whereIn',
            'whereNull', 'whereNotNull', 'limit',
            'orderBy', 'get', 'paginate'
        ]);

        return $this;
    }

    protected function whereInMethod($attribute, $values)
    {
        if (is_array($values)) {

            $valuesArray = [];
            foreach ($values as $value) {
                $this->addValue($attribute, $value);
                array_push($valuesArray, ' ?');
            }
            $condiction = $this->getAttributeName($attribute) . " IN (" . implode(" , ", $valuesArray) . " )";
            $operator = "AND";
            $this->setWhere($operator, $condiction);
            $this->setAllowedMethods([
                'where', 'whereOr', 'whereIn',
                'whereNull', 'whereNotNull', 'limit',
                'orderBy', 'get', 'paginate'
            ]);

            return $this;
        }
    }

    protected function orderByMethod($attribute, $expression)
    {
        $this->setOrderBy($attribute, $expression);
        $this->setAllowedMethods([
            'limit', 'orderBy', 'get', 'paginate'
        ]);

        return $this;
    }

    protected function limitMethod($from, $number)
    {
        $this->setLimit($from, $number);
        $this->setAllowedMethods([
            'limit', 'get', 'paginate'
        ]);
        return $this;
    }

    protected function getMethod($array = [])
    {
        if ($this->sql == '') {
            if (empty($array)) {
                $fields = $this->getTableName() . ".*";
            } else {
                foreach ($array as $key => $field) {
                    $array[$key] = $this->getAttributeName($field);
                }
                $fields = implode(" , ", $array);
            }

            $this->setSql("SELECT $fields FROM " . $this->getTableName());
        }


        $statment = $this->executeQuery();
        $data = $statment->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function paginateMethod($perPage)
    {
        
        $totalRows = $this->getCount();
        $currentPage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $totalPages = ceil($totalRows / $currentPage);
        $currentPage = min($currentPage, $totalPages);
        $currentPage = max($currentPage, 1);
        $currentRow = ($currentPage - 1) * $perPage;
        $this->setLimit($currentRow, $perPage);
        if ($this->sql == '') {
            $this->setSql("SELECT " . $this->getTableName() . ".* FROM " . $this->getTableName());
        }
        $statment = $this->executeQuery();
        $data = $statment->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function findMethod($id)
    {
        $this->setSql("SELECT * FROM " . $this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " =  ? ");
        $this->addValue($this->primaryKey, $id);

        $statment = $this->executeQuery();
        $data = $statment->fetch();
        $this->setAllowedMethods(['update', 'delete', 'save']);
        if ($data) {
            return $this->arrayToAttributes($data);
        }
        return null;
    }


    protected function allMethod()
    {
        $this->setSql("SELECT * FROM " . $this->getTableName());
        $statment = $this->executeQuery();
        $data = $statment->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function deleteMethod($id = null)
    {
        $object = $this;
        $this->resetQuery();
        if ($id) {
            $object->findMethod($id);
            $this->resetQuery();
        }
        $object->setSql("DELETE FROM " . $object->getTableName() . " ");
        $object->setWhere("AND", $this->getAttributeName($this->primaryKey) . " =  ? ");
        $object->addValue($object->primaryKey, $object->{$object->primaryKey});

        return $object->executeQuery();
    }

    protected function saveMethod()
    {
        $fillString = $this->fill();

        if (!isset($this->{$this->primaryKey})) {
            $this->setSql("INSERT INTO " . $this->getTableName() . " SET " . $fillString . ", " . $this->getAttributeName($this->createdAt) . " = Now() ");
        } else {
            $this->setSql("UPDATE " . $this->getTableName() . " SET $fillString , " . $this->getAttributeName($this->updatedAt) . " = Now() ");
            $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " =  ? ");
            $this->addValue($this->primaryKey, $this->{$this->primaryKey});
        }
        $this->executeQuery();
        $this->resetQuery();


        if (!isset($this->{$this->primaryKey})) {
            $object = $this->findMethod(DBConnection::newInsertId());
            $defaultValue = get_class_vars(get_called_class());
            $allValue = get_object_vars($object);
            $differentVars = array_diff(array_keys($allValue), array_keys($defaultValue));
            foreach ($differentVars as $attribute) {
                $this->inCastsAttributes($attribute) == true ? $this->registerAttribute($this, $attribute, $this->castEncodeValue($attribute, $object->$attribute)) : $this->registerAttribute($this, $attribute, $attribute, $object->$attribute);
            }
        }

        $this->resetQuery();
        $this->setAllowedMethods(['update', 'delete', 'find']);
        return $this;
    }

    protected function fill()
    {
        $fillArray = array();

        foreach ($this->fillable as $attribute) {

            if (isset($this->$attribute)) {
                array_push($fillArray, $this->getAttributeName($attribute) . " = ? ");
                $this->inCastsAttributes($attribute) ?  $this->addValue($attribute, $this->castEncodeValue($attribute, $this->$attribute)) : $this->addValue($attribute,  $this->$attribute);
            }
        }

        $fillString = implode(", ", $fillArray);
        return $fillString;
    }
}
