<?php

namespace System\View\Traits;

use Exception;

trait HasExtendsContent
{
    private $extendsContent;

    private function checkExtendsContent()
    {
        $layoutsFilePath = $this->findExtends();
        if ($layoutsFilePath) {
            $this->extendsContent = $this->viewLoader($layoutsFilePath);
            $yieldsNamesArray = $this->findYieldsNames();

            if ($yieldsNamesArray) {
                foreach ($yieldsNamesArray as $yieldsName) {
                    $this->initialYields($yieldsName);
                }
            }

            $this->content = $this->extendsContent;
        }
    }

    private function findExtends()
    {
        $filePathArray = [];
        preg_match("/s*@extends+\('([^)]+)'\)/", $this->content, $filePathArray);
        return isset($filePathArray[1]) ? $filePathArray[1] : false;
    }

    private function findYieldsNames()
    {
        $yieldsNamesArray = [];
        preg_match_all("/@yield+\('([^)]+)'\)/", $this->extendsContent, $yieldsNamesArray, PREG_UNMATCHED_AS_NULL);
        return isset($yieldsNamesArray[1]) ? $yieldsNamesArray[1] : false;
    }


    private function initialYields($yieldsName)
    {
        $string = $this->content;
        $startWord = "@section('" . $yieldsName . "')";
        $endWord = "@endsection";

        $startPos  = strpos($string, $startWord);
        if ($startPos === false) {
            return $this->extendsContent = str_replace("@yield('$yieldsName')", "", $this->extendsContent);
        }

        $startPos += strlen($startWord);
        $endPos = strpos($string, $endWord, $startPos);

        if ($endPos === false) {
            return $this->extendsContent = str_replace("@yield('$yieldsName')", "", $this->extendsContent);
        }

        $length = $endPos - $startPos;
        $sectionContent = substr($string, $startPos, $length);
        return $this->extendsContent = str_replace("@yield('$yieldsName')", $sectionContent, $this->extendsContent);
    }
}
