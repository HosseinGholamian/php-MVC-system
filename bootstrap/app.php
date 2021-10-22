<?php
ob_start();
require_once("../config/app.php");
require_once("../config/database.php");

new \System\Application\Application();
