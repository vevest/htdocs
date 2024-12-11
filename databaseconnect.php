<?php

//definerer variablerne for dataforbindelsen
$host = "localhost"; //host navn på databsen
$dbname = "signup_db"; //navn på selve databsen 
$username = "root";
$password = "root";

//opretter forbindelse til min MYSQL-database
$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);

//tjekker for om der kan connectes                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;