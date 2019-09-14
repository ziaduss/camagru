<?php
    if(!isset($_SESSION))
        session_start();
    include_once "header4.php";
    include "./config/database.php";

    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if(isset($_GET['section']))
    {
        $section = htmlspecialchars($_GET['section']);
    }
    else
    {
        $section = "";
    }

    if (isset($_POST['submit'], $_POST['email']))
    {
        if(!empty($_POST['email']))
        {
            $email = htmlspecialchars($_POST['email']);
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $emailexist = $pdo->prepare("SELECT id, login FROM users WHERE email = ?");
                $emailexist->execute(array($email));
                $emailexist_count = $emailexist->rowCount();
                if($emailexist_count == 1)
                {
                    $login = $emailexist->fetch();
                    $login = $login['login'];
                    $_SESSION['email'] = $email;
                    $recupCode = "";
                    for ($i=0; $i < 8; $i++)
                    {
                        $recupCode .= mt_rand(0,9);
                    }
                    $_SESSION['recupCode'] = $recupCode;

                    $emailrecupexist = $pdo->prepare("SELECT id FROM recup_password WHERE email = ?");
                    $emailrecupexist->execute(array($email));
                    $emailrecupexist = $emailrecupexist->rowCount();

                    if($emailrecupexist == 1)
                    {
                        $recup_insert = $pdo->prepare("UPDATE recup_password SET code = ? WHERE email = ?");
                        $recup_insert->execute(array($recupCode,$email));
                        mail("<".$email.">", "Récupération de mot de passe - camagru", "Bonjour ".$login.", \n\nVoici votre code de récupération:\n $recupCode");
                        header("Location:http://localhost:8100/camagru/resetpassword.php?section=code");
                    }
                    else
                    {
                        $recup_insert = $pdo->prepare("INSERT INTO recup_password(email, code) VALUES (?, ?)");
                        $recup_insert->execute(array($email, $recupCode));
                        mail("<".$email.">", "Récupération de mot de passe - camagru", "Bonjour ".$login.", \n\nVoici votre code de récupération:\n $recupCode");
                        header("Location:http://localhost:8100/camagru/resetpassword.php?section=code");

                    }
                }
                else
                {
                    echo "<p id=err>Adresse mail n'existe pas !</p>";
                }
            }
            else
            {
                echo "<p id=err>Adresse mail invalide !</p>";
            }
        }
        else
        {
            echo "<p id=err>Veuillez entrer votre adresse mail</p>";
        }
    }

    elseif(isset($_POST['submit'], $_POST['recupCode']))
    {
        if(!empty($_POST['recupCode']))
        {
            $recupCode = htmlspecialchars($_POST['recupCode']);
            $verif_req = $pdo->prepare("SELECT id FROM recup_password WHERE email = ? AND code = ?");
            $verif_req->execute(array($_SESSION['email'], $recupCode));
            $verif_req = $verif_req->rowCount();
            if($verif_req == 1)
            {
                $del_req = $pdo->prepare("DELETE FROM recup_password WHERE email = ?");
                $del_req->execute(array($_SESSION['email']));
                header("Location:http://localhost:8100/camagru/resetpassword.php?section=changemdp");
            }
            else
            {
                echo "<p id=err>code invalide</p>";
            }
        }
        else
        {
            echo "<p id=err>Veuillez entrer un code valide</p>";
        }
    }

    else if(isset($_POST['submit']))
    {
        if(isset($_POST['newpassword'], $_POST['verifpassword']))
        {
            $newpassword = htmlspecialchars($_POST['newpassword']);
            $verifpassword = htmlspecialchars($_POST['verifpassword']);
            if(!empty($newpassword) AND !empty($verifpassword))
            {
                if($newpassword == $verifpassword)
                {
                    $newpassword = hash("Whirlpool", $_POST['newpassword']);
                    $insert_password = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $insert_password->execute(array($newpassword, $_SESSION['email']));
                    header("Location:http://localhost:8100/camagru/connection.php");
                }
                else
                {
                    echo "<p id=err>Vos mots de passes ne correspondent pas</p>";
                }
            }
            else
            {
                echo "<p id=err>Veuillez emplir tous les champs</p>";
            }
        }
        else
        {
            echo "<p id=err>Veuillez emplir tous les champs</p>";
        }
    }
    require_once ("newpassword.php");
?>
