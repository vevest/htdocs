<?php

//tjekker for om feltet er udfyldt
if (empty($_POST["name"])) {
    die("Navn er påkrævet");
}

//tjekker for om feltet er udfyldt
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Gyldig e-mail er påkrævet");
}

//tjekker for om adgangskode er udfyldt med korrekte karakterer
if (strlen($_POST["password"]) < 8) {
    die("Adgangskode skal være mindst 8 tegn");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Adgangskode skal indeholde mindst et bogstav");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Adgangskode skal indeholde mindst et tal");
}

//tjekker for om adgangskoder er ens
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Adgangskoder skal stemme overens");
}

//tjekker om terms er accepteret
if (empty($_POST["terms"])) {
    die("Du skal acceptere vilkår og betingelser for at tilmelde dig");
}

//hasher adgangskoden så den transformeres til en uigenkendelig string. Password_default er algoritme der automatisk opdateres og hasher adgangskode på den sikreste måde
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

//opretter forbindelse til databasen
$mysqli = require __DIR__ . "/databaseconnect.php";

// values(?, ?, ?) bruges som placeholder for værdierne for at beskytte mod SQL-injection
$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";
  
//opretter tomt statement objekt  
$stmt = $mysqli->stmt_init();

//bruger objektet her til at tjekke for evt syntax fejl eller ugyldig forespørgsler
if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

//binder parametre til SQL-stamentet (øger sikkerheden) "sss" står for 3 strings
$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
 
//udfører statmentet med if else  
//hvis if så er det successfuld                
if ($stmt->execute()) {

    header("Location: success_signup.html");
    exit;
    
} else {
    //hvis emailen er brugt før
    if ($mysqli->errno === 1062) {
        die("Denne e-mail er allerede i brug");
    } else {
        // Andre fejl der kunne være vises med fejlkode
        die($mysqli->error . " " . $mysqli->errno);
    }
}







