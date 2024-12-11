<?php

//opretter forbindelse til database
$mysqli = require __DIR__ . "/databaseconnect.php";

//finder specifik email og tjekker for specieltegn håndteres korrekt med real_escape_string 
$sql = sprintf("SELECT * FROM user
                WHERE email = '%s'",
                $mysqli->real_escape_string($_GET["email"]));

//gemmer resultatet
$result = $mysqli->query($sql);

//tjekker om emailen allerede findes i resultatet
$is_available = $result->num_rows === 0;

// Sætter HTTP-headeren til at indikere, at vi sender JSON-data tilbage
header("Content-Type: application/json");

// Konverterer $is_available til en JSON-struktur og sender den som svaret
echo json_encode(["available" => $is_available]);