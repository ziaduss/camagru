<?php
    if(!isset($_SESSION))
        session_start();
    if (!isset($_SESSION['login']) && empty($_SESSION['login']))
        include_once "header3.php";
    else if (isset($_SESSION['login']) && !empty($_SESSION['login']))
    {
        include_once "header2.php";
        echo "<p id=user>Bonjour <i>".$_SESSION['login']."</i></p>";
    }
    else
        include_once "header1.php";
?>
