<?php

//starter session
session_start();

//ødelægger session og fjerne alt data
session_destroy();

header("Location: index.php");
exit;