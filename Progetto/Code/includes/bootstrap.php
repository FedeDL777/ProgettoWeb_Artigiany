<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("UPLOAD_DIR", "./upload/");
require_once("../database/Database.php");
require_once("functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "db_artigiany", 3306);
insertAdmin($dbh);