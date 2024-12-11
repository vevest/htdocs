<?php

//tjekker om login er ugyldigt
$is_invalid = false;

//tjekker om brugeren forsøger at logge ind
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    //opretter forbindelse til database
    $mysqli = require __DIR__ . "/databaseconnect.php";
    
    //laver forspørgsmål der henter brugerens login info 
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    //gemmer resultatet               
    $result = $mysqli->query($sql);
    
    //tjekker for om brugeren findes
    $user = $result->fetch_assoc();
    
    //hvis brugeren email findes
    if ($user) {
        
        //tjekker adgangskoden passer
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            //starter en session så brugeren kan være logget på
            session_start();
            
            //fornyer session ID for sikkerheds optimering
            session_regenerate_id();
            
            //gemmer sessionens ID for brugeridentificering 
            $_SESSION["user_id"] = $user["id"];
            
            //omdirigerer bruger til index
            header("Location: index.php");
            exit;
        }
    }
    
    //hvis login fejler skriver fejlmeddelse
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <em>Ugyldigt login</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Adgangskode</label>
        <input type="password" name="password" id="password">
        
        <button>Log ind</button>
    </form>
    
</body>
</html>
