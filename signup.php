<?php
// Initialiserer en variabel for fejl
$errorMessage = '';

// Tjekker for om feltet er udfyldt
if (empty($_POST["name"])) {
    $errorMessage .= "Navn er påkrævet\n";
}

// Tjekker for om e-mailen er gyldig
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errorMessage .= "Gyldig e-mail er påkrævet\n";
}

// Tjekker for om adgangskoden er korrekt oprettet på forskellige parametre
if (strlen($_POST["password"]) < 8) {
    $errorMessage .= "Adgangskode skal være mindst 8 tegn\n";
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    $errorMessage .= "Adgangskode skal indeholde mindst et bogstav\n";
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    $errorMessage .= "Adgangskode skal indeholde mindst et tal\n";
}

// Tjekker om adgangskoderne er ens
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    $errorMessage .= "Adgangskoderne skal stemme overens\n";
}

// Tjekker om vilkår er accepteret
if (empty($_POST["terms"])) {
    $errorMessage .= "Du skal acceptere vilkår og betingelser\n";
}

// Hvis der er fejl, sender beskeden tilbage til tilmeldingsformularen
if ($errorMessage) {
    echo "<script>alert('$errorMessage');</script>";
    exit;
}

// Hvis alt er OK, fortsætter med at gemme i databasen
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$mysqli = require __DIR__ . "/databaseconnect.php";
$sql = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

if ($stmt->execute()) {
    header("Location: success_signup.html");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Denne e-mail er allerede i brug");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}






