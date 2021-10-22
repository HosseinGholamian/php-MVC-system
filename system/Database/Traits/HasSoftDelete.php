<?php

namespace System\Database\Traits;

trait HasSoftDelete
{


    protected function paginateMethod($perPage)
    {
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");
        $totalRows = $this->getCount();
        $currentPage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
        $totalPages = ceil($totalRows / $currentPage);
        $currentPage = min($currentPage, $totalPages);
        $currentPage = max($currentPage, 1);
        $currentRow = ($currentPage - 1) * $perPage;
        $this->setLimit($currentRow, $perPage);
        if ($this->sql = '') {
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
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");
        $statment = $this->executeQuery();
        $data = $statment->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function allMethod()
    {
        $this->setSql("SELECT * FROM " . $this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");
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
        $this->resetQuery();
        $this->setSql("SELECT * FROM " . $this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " =  ? ");
        $this->addValue($this->primaryKey, $id);

        $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");

        $statment = $this->executeQuery();
        $data = $statment->fetch();
        $this->setAllowedMethods(['update', 'delete', 'find']);
        if ($data) {
            return $this->arrayToAttribute($data);
        }
        return null;
    }




    protected function deleteMethod($id = null)
    {
        $object = $this;

        if ($id) {
            $this->resetQuery();
            $object->findMethod($id);
        }
        if ($object) {
            $object->resetQuery();
            $object->setSql("UPDATE  " . $object->getTableName() . " SET " . $object->getAttributeName($this->deletedAt) . " = NOW()");
            $object->setWhere("AND", $this->getAttributeName($object->primaryKey) . " =  ? ");
            $object->addValue($object->primaryKey, $object->{$object->primaryKey});
            return $object->executeQuery();
        }
    }
}
