<?php

namespace System\Request\Traits;

use System\Database\DBConnection\DBConnection;


trait HasValidationRules
{

    protected function maxStr($name, $count)
    {
        if ($this->checkFeildExist($name)) {
            if (strlen($this->request[$name]) > $count && $this->checkFirstError($name)) {
                $this->setError($name, "max length must be lower than $count character");
            }
        }
    }

    protected function maxNumber($name, $count)
    {
        if ($this->checkFeildExist($name)) {
            if ($this->request[$name] > $count && $this->checkFirstError($name)) {
                $this->setError($name, "max length must be lower than $count ");
            }
        }
    }

    protected function minStr($name, $count)
    {
        if ($this->checkFeildExist($name)) {
            if (strlen($this->request[$name]) < $count && $this->checkFirstError($name)) {
                $this->setError($name, "min length must be upper than $count character");
            }
        }
    }

    protected function minNumber($name, $count)
    {
        if ($this->checkFeildExist($name)) {
            if ($this->request[$name] < $count && $this->checkFirstError($name)) {
                $this->setError($name, "min length must be upper than $count character");
            }
        }
    }


    protected function required($name)
    {
        if ((!isset($this->request[$name]) || $this->request[$name] === '') &&  $this->checkFirstError($name)) {
            $this->setError($name, "$name is required ");
        }
    }

    protected function number($name)
    {
        if ($this->checkFeildExist($name)) {
            if (!is_numeric($this->request[$name]) &&  $this->checkFeildExist($name)) {
                $this->setError($name, "$name must be number");
            }
        }
    }


    protected function date($name)
    {
        if ($this->checkFeildExist($name)) {
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->request[$name]) && $this->checkFirstError($name)) {
                $this->setError($name, "$name must be date format");
            }
        }
    }

    protected function email($name)
    {
        if ($this->checkFeildExist($name)) {
            if (!filter_var($this->request[$name], FILTER_VALIDATE_EMAIL) && $this->checkFirstError($name)) {
                $this->setError($name, "$name must be email format");
            }
        }
    }


    public function existsIn($name, $table, $field = 'id')
    {
        if ($this->checkFeildExist($name)) {
            if ($this->checkFirstError($name)) {
                $value = $this->$name;
                $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
                $statement = DBConnection::getDBConnectionInstance()->prepare($sql);
                $statement->execute([$value]);
                $result = $statement->fetchColumn();
                if ($result == 0 || $result === false) {
                    $this->setError($name, "$name not already exist");
                }
            }
        }
    }

    public function unique($name, $table, $field = 'id')
    {
        if ($this->checkFeildExist($name)) {
            if ($this->checkFirstError($name)) {
                $value = $this->$name;
                $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
                $statement = DBConnection::getDBConnectionInstance()->prepare($sql);
                $statement->execute([$value]);
                $result = $statement->fetchColumn();
                if ($result != 0 ) {
                    $this->setError($name, "$name must be unique");
                }
            }
        }
    }

    public function confirm($name)
    {
        if ($this->checkFeildExist($name)) {
            $fieldName = "confirm_".$name;
            if(!isset($fieldName)){
                $this->setError($name , " $name not exists");
            }

            if($this->$name != $this->$fieldName){
                $this->setError($name , " $name confirmation does not match");
            }
        }
    }

    public function normalValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule) {
            if ($rule == "required") {
                $this->required($name);
            } elseif (strpos($rule, "max:") === 0) {
                $rule = str_replace("max:", "", $rule);
                $this->maxStr($name, $rule);
            } elseif (strpos("min:", $rule) === 0) {
                $rule = str_replace("min:", "", $rule);
                $this->minStr($name, $rule);
            } elseif (strpos("exist:", $rule) === 0) {
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(",", $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];

                $this->existIn($name, $rule[0], $key);
            } elseif ($rule == "email") {
                $this->email($name);
            } elseif ($rule == "date") {
                $this->date($name);
            }
        }
    }


    public function numberValidation($name, $ruleArray)
    {
        foreach ($ruleArray as $rule) {
            if ($rule == "required") {
                $this->required($name);
            } elseif (strpos($rule, "max:") === 0) {
                $rule = str_replace("max:", "", $rule);
                $this->maxNumber($name, $rule);
            } elseif (strpos("min:", $rule) === 0) {
                $rule = str_replace("min:", "", $rule);
                $this->minNumber($name, $rule);
            } elseif (strpos("exist:", $rule) === 0) {
                $rule = str_replace("exists:", "", $rule);
                $rule = explode(",", $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existIn($name, $rule[0], $key);
            } elseif ($rule == "number") {
                $this->number($name);
            }
        }
    }
}
