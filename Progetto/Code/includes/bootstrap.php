<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("UPLOAD_DIR", "./upload/");
require_once("../database/Database.php");
#da sostituire con i dati del proprio database
$db = new Database("db", "root", "", "artigiany", 3306);