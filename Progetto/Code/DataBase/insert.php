<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "DB_Artigiany";

$connection = new mysqli($host, $user, $password, $database);

if ($connection === false) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Connected successfully" . $connection->host_info;
$email = $connection->real_escape_string($_REQUEST['email']);
$password = $connection->real_escape_string($_REQUEST['password']);

$sql = "INSERT INTO Users (Email, Password, AdminClient) 
VALUES ('$email', '$password', 0)";
if($connection->query($sql) === true){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . $connection->error;
}

$connection->close();
?>