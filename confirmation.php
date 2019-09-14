<?php
    if(!isset($_SESSION))
        session_start();
    include "./config/database.php";

    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if(isset($_GET['login'], $_GET['key']) AND !empty($_GET['login']) AND !empty($_GET['key']))
    {
        $login = htmlspecialchars(urldecode($_GET['login']));
        $key = htmlspecialchars($_GET['key']);

        $reqLogin = $pdo->prepare("SELECT * FROM users WHERE login = ? AND confirmekey = ?");
        $reqLogin->execute(array($login, $key));
        $loginExist = $reqLogin->rowCount();

        if($loginExist == 1)
        {
            $user = $reqLogin->fetch();
            if($user['confirme'] == 0)
            {
                $updateLogin = $pdo->prepare("UPDATE users SET confirme = 1 WHERE login = ? AND confirmekey = ?");
                $updateLogin->execute(array($login, $key));
                header("Location: connection.php?err=Votre compte a bien été confirmé !");
            }
            else
            {
                echo "Votre compte a déjà été confirmé !";
            }

        }
        else
        {
            echo "L'utilisateur n'existe pas !";
        }

    }
?>