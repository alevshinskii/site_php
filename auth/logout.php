<?php

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $_SESSION["loggedin"]=false;
}  
session_destroy();
header("location: ../index.php");
exit;