<?php

namespace Models;

abstract class HuddleObj
{
    protected $dbTable;
    protected $primaryKeysArray = [];
    protected $extraAttr = [];

    public static function create()
    {
        return new static();
    }

    public function getDbTable()
    {
        if($this->dbTable == null)
        {
            throw new \Exception("dbTable not defined in class ".get_class());
        }
        return $this->dbTable;
    }

    public function getPrimaryKeysArray()
    {
        if($this->primaryKeysArray == null)
        {
            throw new \Exception("Primary Keys not defined in class ".get_class());
        }
        return $this->primaryKeysArray;
    }

    public function getExtraAttr()
    {
        return $this->extraAttr;
    }

    protected function setDbTable($tableName)
    {
        $this->dbTable = $tableName;
    }

    protected function setPrimaryKeysArray(array $array)
    {
        $this->primaryKeysArray = $array;
    }

    protected function setExtraAttr(array $array)
    {
        $this->extraAttr = $array;
    }
}