<?php

namespace System\Database\DBBuilder;

use System\Database\DBConnection\DBConnection;
use System\Config\Config;

class DBBuilder
{

    public function __construct()
    {
        $this->createTables();
        die("table created successfully");
    }

    private function createTables()
    {
        $migrations = $this->getMigarations();
        $pdoInstance = DBConnection::getDBConnectionInstance();
        foreach ($migrations as $migration) {
            $statment = $pdoInstance->prepare($migration);
            $statment->execute();
        }

        return true;
    }

    private function getMigarations()
    {
        $oldMigrationsArray = $this->getOldMigration();
        $migrationDirectory =  Config::get("app.BASE_DIR") . '' . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "migrations" . DIRECTORY_SEPARATOR;
        $allMigrationsArray =  glob($migrationDirectory . "*.php");
        $newMigrationsArray = array_diff($allMigrationsArray, $oldMigrationsArray);

        $this->putOldMigration($allMigrationsArray);

        $sqlCodeArray = [];
        foreach ($newMigrationsArray as $filename) {
            $sqlCode = require $filename;
            array_push($sqlCodeArray, $sqlCode[0]);
        }

        return $sqlCodeArray;
    }

    private function getOldMigration()
    {
        $data = file_get_contents(__DIR__ . "" . DIRECTORY_SEPARATOR . "oldTables.db");
        return empty($data) ? [] : unserialize($data);
    }
    private function putOldMigration($value)
    {
        file_put_contents(__DIR__ . '/oldTables.db', serialize($value));
    }
}
