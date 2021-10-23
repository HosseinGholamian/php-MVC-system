<?php

namespace System\Request\Traits;


trait HasRunValidation
{
    protected function errorRedirect()
    {
        if ($this->errorExist === false) {
            return $this->request;
        }
        return back();
    }

    protected function checkFirstError($name)
    {
        if (!errorExists($name) && !in_array($name, $this->errorVariableName)) {
            return true;
        }
        return false;
    }

    protected function checkFeildExist($name)
    {
        return (isset($this->request[$name]) && !empty($this->request[$name])) ? true : false;
    }

    protected function checkFileExist($name)
    {
        if (isset($this->files[$name]['name'])) {
            if (!empty($this->files[$name]['name'])) {
                return true;
            }
        }
        return false;
    }

    private function setError($name, $errorMessage)
    {
        array_push($this->errorVariableName, $name);
        error($name, $errorMessage);
        $this->errorExist = true;
    }
}
