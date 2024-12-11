<?php
//starter session
session_start();

//tjekker om bruger logget ind
if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/databaseconnect.php";
    
    //henter den bruger der er logget inds data baseret på brugerens id i SQL-databasen
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
    
    //gemmer resultatet
    $result = $mysqli->query($sql);
    
    //henter brugerens informatio
    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    
    <h1>Velkommen</h1>
    
    <?php if (isset($user)): ?>
        
        <p>Hej <?= htmlspecialchars($user["name"]) ?></p>
        
        <p><a href="logout.php">Log ud</a></p>
        
    <?php else: ?>
        
        <p><a href="form.html">Tilmeld dig her</a> <br> <br> Har du allerede en bruger? <br> <a href="login.php">Log ind</a></p>
        
    <?php endif; ?>
    
</body>
</html>
    
    